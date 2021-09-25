<?php
namespace Symfony\Component\Finder\Tests\Iterator;
class FilterIteratorTest extends RealIteratorTestCase
{
    public function testFilterFilesystemIterators()
    {
        $i = new \FilesystemIterator($this->toAbsolute());
        $i = $this->getMockForAbstractClass('Symfony\Component\Finder\Iterator\FilterIterator', array($i));
        $i->expects($this->any())
            ->method('accept')
            ->will($this->returnCallback(function () use ($i) {
                return (bool) preg_match('/\.php/', (string) $i->current());
            })
        );
        $c = 0;
        foreach ($i as $item) {
            $c++;
        }
        $this->assertEquals(1, $c);
        $i->rewind();
        $c = 0;
        foreach ($i as $item) {
            $c++;
        }
        $this->assertEquals(1, $c);
    }
}