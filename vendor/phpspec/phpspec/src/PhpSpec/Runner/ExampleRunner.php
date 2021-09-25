<?php
namespace PhpSpec\Runner;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PhpSpec\Runner\Maintainer\LetAndLetgoMaintainer;
use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\SpecificationInterface;
use PhpSpec\Event\ExampleEvent;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Exception\Exception as PhpSpecException;
use PhpSpec\Exception\Example as ExampleException;
use Prophecy\Exception as ProphecyException;
use Exception;
class ExampleRunner
{
    private $dispatcher;
    private $presenter;
    private $maintainers = array();
    public function __construct(EventDispatcherInterface $dispatcher, PresenterInterface $presenter)
    {
        $this->dispatcher = $dispatcher;
        $this->presenter  = $presenter;
    }
    public function registerMaintainer(Maintainer\MaintainerInterface $maintainer)
    {
        $this->maintainers[] = $maintainer;
        @usort($this->maintainers, function ($maintainer1, $maintainer2) {
            return $maintainer2->getPriority() - $maintainer1->getPriority();
        });
    }
    public function run(ExampleNode $example)
    {
        $startTime = microtime(true);
        $this->dispatcher->dispatch(
            'beforeExample',
            new ExampleEvent($example)
        );
        try {
            $this->executeExample(
                $example->getSpecification()->getClassReflection()->newInstance(),
                $example
            );
            $status    = ExampleEvent::PASSED;
            $exception = null;
        } catch (ExampleException\PendingException $e) {
            $status    = ExampleEvent::PENDING;
            $exception = $e;
        } catch (ExampleException\SkippingException $e) {
            $status    = ExampleEvent::SKIPPED;
            $exception = $e;
        } catch (ProphecyException\Prediction\PredictionException $e) {
            $status    = ExampleEvent::FAILED;
            $exception = $e;
        } catch (ExampleException\FailureException $e) {
            $status    = ExampleEvent::FAILED;
            $exception = $e;
        } catch (Exception $e) {
            $status    = ExampleEvent::BROKEN;
            $exception = $e;
        }
        if ($exception instanceof PhpSpecException) {
            $exception->setCause($example->getFunctionReflection());
        }
        $runTime = microtime(true) - $startTime;
        $this->dispatcher->dispatch(
            'afterExample',
            $event = new ExampleEvent($example, $runTime, $status, $exception)
        );
        return $event->getResult();
    }
    protected function executeExample(SpecificationInterface $context, ExampleNode $example)
    {
        if ($example->isPending()) {
            throw new ExampleException\PendingException();
        }
        $matchers      = new MatcherManager($this->presenter);
        $collaborators = new CollaboratorManager($this->presenter);
        $maintainers   = array_filter($this->maintainers, function ($maintainer) use ($example) {
            return $maintainer->supports($example);
        });
        foreach ($maintainers as $maintainer) {
            $maintainer->prepare($example, $context, $matchers, $collaborators);
        }
        $reflection = $example->getFunctionReflection();
        try {
            $reflection->invokeArgs($context, $collaborators->getArgumentsFor($reflection));
        } catch (\Exception $e) {
            $this->runMaintainersTeardown(
                $this->searchExceptionMaintainers($maintainers),
                $example,
                $context,
                $matchers,
                $collaborators
            );
            throw $e;
        }
        $this->runMaintainersTeardown($maintainers, $example, $context, $matchers, $collaborators);
    }
    private function runMaintainersTeardown(
        array $maintainers,
        ExampleNode $example,
        SpecificationInterface $context,
        MatcherManager $matchers,
        CollaboratorManager $collaborators
    ) {
        foreach (array_reverse($maintainers) as $maintainer) {
            $maintainer->teardown($example, $context, $matchers, $collaborators);
        }
    }
    private function searchExceptionMaintainers(array $maintainers)
    {
        return array_filter(
            $maintainers,
            function ($maintainer) {
                return $maintainer instanceof LetAndLetgoMaintainer;
            }
        );
    }
}
