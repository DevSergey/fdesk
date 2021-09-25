<?php
namespace Prophecy\Doubler;
use ReflectionClass;
class CachedDoubler extends Doubler
{
    private $classes = array();
    public function registerClassPatch(ClassPatch\ClassPatchInterface $patch)
    {
        $this->classes[] = array();
        parent::registerClassPatch($patch);
    }
    protected function createDoubleClass(ReflectionClass $class = null, array $interfaces)
    {
        $classId = $this->generateClassId($class, $interfaces);
        if (isset($this->classes[$classId])) {
            return $this->classes[$classId];
        }
        return $this->classes[$classId] = parent::createDoubleClass($class, $interfaces);
    }
    private function generateClassId(ReflectionClass $class = null, array $interfaces)
    {
        $parts = array();
        if (null !== $class) {
            $parts[] = $class->getName();
        }
        foreach ($interfaces as $interface) {
            $parts[] = $interface->getName();
        }
        sort($parts);
        return md5(implode('', $parts));
    }
}