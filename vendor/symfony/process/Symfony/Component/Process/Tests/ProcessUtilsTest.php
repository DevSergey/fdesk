<?php
namespace Symfony\Component\Process\Tests;
use Symfony\Component\Process\ProcessUtils;
class ProcessUtilsTest extends \PHPUnit_Framework_TestCase
{
    public function testEscapeArgument($result, $argument)
    {
        $this->assertSame($result, ProcessUtils::escapeArgument($argument));
    }
    public function dataArguments()
    {
        if ('\\' === DIRECTORY_SEPARATOR) {
            return array(
                array('"\"php\" \"-v\""', '"php" "-v"'),
                array('"foo bar"', 'foo bar'),
                array('^%"path"^%', '%path%'),
                array('"<|>\\" \\"\'f"', '<|>" "\'f'),
                array('""', ''),
                array('"with\trailingbs\\\\"', 'with\trailingbs\\'),
            );
        }
        return array(
            array("'\"php\" \"-v\"'", '"php" "-v"'),
            array("'foo bar'", 'foo bar'),
            array("'%path%'", '%path%'),
            array("'<|>\" \"'\\''f'", '<|>" "\'f'),
            array("''", ''),
            array("'with\\trailingbs\\'", 'with\trailingbs\\'),
        );
    }
}