<?php
class CoverageNotProtectedTest extends PHPUnit_Framework_TestCase
{
    public function testSomething()
    {
        $o = new CoveredClass;
        $o->publicMethod();
    }
}
