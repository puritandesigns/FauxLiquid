<?php

namespace FauxLiquid\Node;

class UnlessNode extends \Twig\Node\IfNode
{
    public function __construct(
        \Twig\Node\Node $tests,
        ?\Twig\Node\Node $else,
        int $lineno,
        string $tag = null
    )
    {
        parent::__construct($tests, $else, $lineno, $tag);
    }

    public function getTag()
    {
        return 'unless';
    }

    public function compile(\Twig\Compiler $compiler): void
    {
        $compiler->addDebugInfo($this);
        for ($i = 0, $count = \count($this->getNode('tests')); $i < $count; $i += 2) {
            if ($i > 0) {
                $compiler
                    ->outdent()
                    ->write('} elseif (!(')
                ;
            } else {
                $compiler
                    ->write('if (!(')
                ;
            }

            $compiler
                ->subcompile($this->getNode('tests')->getNode($i))
                ->raw(")) {\n")
                ->indent()
                ->subcompile($this->getNode('tests')->getNode($i + 1))
            ;
        }

        if ($this->hasNode('else')) {
            $compiler
                ->outdent()
                ->write("} else {\n")
                ->indent()
                ->subcompile($this->getNode('else'))
            ;
        }

        $compiler
            ->outdent()
            ->write("}\n");
    }
}
