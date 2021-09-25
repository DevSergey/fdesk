<?php
namespace Symfony\Component\Routing\Matcher\Dumper;
class DumperCollection implements \IteratorAggregate
{
    private $parent;
    private $children = array();
    private $attributes = array();
    public function all()
    {
        return $this->children;
    }
    public function add($child)
    {
        if ($child instanceof DumperCollection) {
            $child->setParent($this);
        }
        $this->children[] = $child;
    }
    public function setAll(array $children)
    {
        foreach ($children as $child) {
            if ($child instanceof DumperCollection) {
                $child->setParent($this);
            }
        }
        $this->children = $children;
    }
    public function getIterator()
    {
        return new \ArrayIterator($this->children);
    }
    public function getRoot()
    {
        return (null !== $this->parent) ? $this->parent->getRoot() : $this;
    }
    protected function getParent()
    {
        return $this->parent;
    }
    protected function setParent(DumperCollection $parent)
    {
        $this->parent = $parent;
    }
    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }
    public function getAttribute($name, $default = null)
    {
        return $this->hasAttribute($name) ? $this->attributes[$name] : $default;
    }
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    }
}
