<?php
class PHPUnit_Framework_Constraint_GreaterThan extends PHPUnit_Framework_Constraint
{
    protected $value;
    public function __construct($value)
    {
        parent::__construct();
        $this->value = $value;
    }
    protected function matches($other)
    {
        return $this->value < $other;
    }
    public function toString()
    {
        return 'is greater than ' . $this->exporter->export($this->value);
    }
}