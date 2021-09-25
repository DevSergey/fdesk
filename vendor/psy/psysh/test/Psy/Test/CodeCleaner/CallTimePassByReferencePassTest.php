<?php
namespace Psy\Test\CodeCleaner;
use PHPParser_NodeTraverser as NodeTraverser;
use Psy\CodeCleaner\CallTimePassByReferencePass;
class CallTimePassByReferencePassTest extends CodeCleanerTestCase
{
    public function setUp()
    {
        $this->pass      = new CallTimePassByReferencePass();
        $this->traverser = new NodeTraverser();
        $this->traverser->addVisitor($this->pass);
    }
    public function testProcessStatementFails($code)
    {
        if (version_compare(PHP_VERSION, '5.4', '<')) {
            $this->markTestSkipped();
        }
        $stmts = $this->parse($code);
        $this->traverser->traverse($stmts);
    }
    public function invalidStatements()
    {
        return array(
            array('f(&$arg)'),
            array('$object->method($first, &$arg)'),
            array('$closure($first, &$arg, $last)'),
            array('A::b(&$arg)'),
        );
    }
    public function testProcessStatementPasses($code)
    {
        $stmts = $this->parse($code);
        $this->traverser->traverse($stmts);
    }
    public function validStatements()
    {
        $data = array(
            array('array(&$var)'),
            array('$a = &$b'),
            array('f(array(&$b))'),
        );
        if (version_compare(PHP_VERSION, '5.4', '<')) {
            $data = array_merge($data, $this->invalidStatements());
        }
        return $data;
    }
}
