<?php
namespace PhpParser\Node\Expr;
use PhpParser\Node\Expr;
class ErrorSuppress extends Expr
{
    public $expr;
    public function __construct(Expr $expr, array $attributes = array()) {
        parent::__construct(null, $attributes);
        $this->expr = $expr;
    }
    public function getSubNodeNames() {
        return array('expr');
    }
}