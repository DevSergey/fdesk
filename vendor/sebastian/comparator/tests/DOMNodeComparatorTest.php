<?php
namespace SebastianBergmann\Comparator;
use DOMNode;
use DOMDocument;
class DOMNodeComparatorTest extends \PHPUnit_Framework_TestCase
{
    private $comparator;
    protected function setUp()
    {
        $this->comparator = new DOMNodeComparator;
    }
    public function acceptsSucceedsProvider()
    {
        $document = new DOMDocument;
        $node = new DOMNode;
        return array(
          array($document, $document),
          array($node, $node),
          array($document, $node),
          array($node, $document)
        );
    }
    public function acceptsFailsProvider()
    {
        $document = new DOMDocument;
        return array(
          array($document, null),
          array(null, $document),
          array(null, null)
        );
    }
    public function assertEqualsSucceedsProvider()
    {
        return array(
          array(
            $this->createDOMDocument('<root></root>'),
            $this->createDOMDocument('<root/>')
          ),
          array(
            $this->createDOMDocument('<root attr="bar"></root>'),
            $this->createDOMDocument('<root attr="bar"/>')
          ),
          array(
            $this->createDOMDocument('<root><foo attr="bar"></foo></root>'),
            $this->createDOMDocument('<root><foo attr="bar"/></root>')
          ),
          array(
            $this->createDOMDocument("<root>\n  <child/>\n</root>"),
            $this->createDOMDocument('<root><child/></root>')
          ),
        );
    }
    public function assertEqualsFailsProvider()
    {
        return array(
          array(
            $this->createDOMDocument('<root></root>'),
            $this->createDOMDocument('<bar/>')
          ),
          array(
            $this->createDOMDocument('<foo attr1="bar"/>'),
            $this->createDOMDocument('<foo attr1="foobar"/>')
          ),
          array(
            $this->createDOMDocument('<foo> bar </foo>'),
            $this->createDOMDocument('<foo />')
          ),
          array(
            $this->createDOMDocument('<foo xmlns="urn:myns:bar"/>'),
            $this->createDOMDocument('<foo xmlns="urn:notmyns:bar"/>')
          ),
          array(
            $this->createDOMDocument('<foo> bar </foo>'),
            $this->createDOMDocument('<foo> bir </foo>')
          )
        );
    }
    private function createDOMDocument($content)
    {
        $document = new DOMDocument;
        $document->preserveWhiteSpace = false;
        $document->loadXML($content);
        return $document;
    }
    public function testAcceptsSucceeds($expected, $actual)
    {
        $this->assertTrue(
          $this->comparator->accepts($expected, $actual)
        );
    }
    public function testAcceptsFails($expected, $actual)
    {
        $this->assertFalse(
          $this->comparator->accepts($expected, $actual)
        );
    }
    public function testAssertEqualsSucceeds($expected, $actual)
    {
        $exception = null;
        try {
            $this->comparator->assertEquals($expected, $actual);
        }
        catch (ComparisonFailure $exception) {
        }
        $this->assertNull($exception, 'Unexpected ComparisonFailure');
    }
    public function testAssertEqualsFails($expected, $actual)
    {
        $this->setExpectedException(
          'SebastianBergmann\\Comparator\\ComparisonFailure',
          'Failed asserting that two DOM'
        );
        $this->comparator->assertEquals($expected, $actual);
    }
}