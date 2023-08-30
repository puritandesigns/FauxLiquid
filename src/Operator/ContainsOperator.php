<?php

namespace FauxLiquid\Operator;

use Twig\Compiler;
use Twig\Node\Expression\Binary\AbstractBinary;

class ContainsOperator extends AbstractBinary
{
    public function compile(Compiler $compiler) : void
    {
        $compiler
            ->raw('(\\FauxLiquid\\Support\\Operators::contains(')
            ->subcompile($this->getNode('left'))
            ->raw(', ')
            ->subcompile($this->getNode('right'))
            ->raw('))')
        ;
    }

    public function operator(Compiler $compiler) : Compiler
    {
        return $compiler->raw('');
    }
}
