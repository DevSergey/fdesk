<?php
namespace Symfony\Component\VarDumper\Caster;
use Symfony\Component\VarDumper\Cloner\Stub;
class ConstStub extends Stub
{
    public function __construct($name, $value)
    {
        $this->class = $name;
        $this->value = $value;
    }
}