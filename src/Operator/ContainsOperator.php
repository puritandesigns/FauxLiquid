<?php

namespace FauxLiquid\Operator;

use Twig\Compiler;
use Twig\Node\Expression\Binary\AbstractBinary;

class ContainsOperator extends AbstractBinary
{
    public function compile(Compiler $compiler) : void
    {
        $compiler
            ->raw('(in_array(')
            ->subcompile($this->getNode('right'))
            ->raw(', ')
            ->subcompile($this->getNode('left'))
            ->raw('))')
        ;
    }

    public function operator(Compiler $compiler) : Compiler
    {
        return $compiler->raw('');
    }
}
