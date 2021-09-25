<?php
namespace SebastianBergmann\Comparator;
class ScalarComparatorTest extends \PHPUnit_Framework_TestCase
{
    private $comparator;
    protected function setUp()
    {
        $this->comparator = new ScalarComparator;
    }
    public function acceptsSucceedsProvider()
    {
        return array(
          array("string", "string"),
          array(new ClassWithToString, "string"),
          array("string", new ClassWithToString),
          array("string", null),
          array(false, "string"),
          array(false, true),
          array(null, false),
          array(null, null),
          array("10", 10),
          array("", false),
          array("1", true),
          array(1, true),
          array(0, false),
          array(0.1, "0.1")
        );
    }
    public function acceptsFailsProvider()
    {
        return array(
          array(array(), array()),
          array("string", array()),
          array(new ClassWithToString, new ClassWithToString),
          array(false, new ClassWithToString),
          array(tmpfile(), tmpfile())
        );
    }
    public function assertEqualsSucceedsProvider()
    {
        return array(
          array("string", "string"),
          array(new ClassWithToString, new ClassWithToString),
          array("string representation", new ClassWithToString),
          array(new ClassWithToString, "string representation"),
          array("string", "STRING", true),
          array("STRING", "string", true),
          array("String Representation", new ClassWithToString, true),
          array(new ClassWithToString, "String Representation", true),
          array("10", 10),
          array("", false),
          array("1", true),
          array(1, true),
          array(0, false),
          array(0.1, "0.1"),
          array(false, null),
          array(false, false),
          array(true, true),
          array(null, null)
        );
    }
    public function assertEqualsFailsProvider()
    {
        $stringException = 'Failed asserting that two strings are equal.';
        $otherException = 'matches expected';
        return array(
          array("string", "other string", $stringException),
          array("string", "STRING", $stringException),
          array("STRING", "string", $stringException),
          array("string", "other string", $stringException),
          array('9E6666666','9E7777777', $stringException),
          array(new ClassWithToString, "does not match", $otherException),
          array("does not match", new ClassWithToString, $otherException),
          array(0, 'Foobar', $otherException),
          array('Foobar', 0, $otherException),
          array("10", 25, $otherException),
          array("1", false, $otherException),
          array("", true, $otherException),
          array(false, true, $otherException),
          array(true, false, $otherException),
          array(null, true, $otherException),
          array(0, true, $otherException)
        );
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
    public function testAssertEqualsSucceeds($expected, $actual, $ignoreCase = false)
    {
        $exception = null;
        try {
            $this->comparator->assertEquals($expected, $actual, 0.0, false, $ignoreCase);
        }
        catch (ComparisonFailure $exception) {
        }
        $this->assertNull($exception, 'Unexpected ComparisonFailure');
    }
    public function testAssertEqualsFails($expected, $actual, $message)
    {
        $this->setExpectedException(
          'SebastianBergmann\\Comparator\\ComparisonFailure', $message
        );
        $this->comparator->assertEquals($expected, $actual);
    }
}
