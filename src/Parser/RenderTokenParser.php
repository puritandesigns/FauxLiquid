<?php

namespace FauxLiquid\Parser;

use Twig\Node\IncludeNode;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\IncludeTokenParser;

class RenderTokenParser extends IncludeTokenParser
{
    public function parse(Token $token): Node
    {
        /** @var \Twig\Node\Expression\ConstantExpression $expr */
        $expr = $this->parser->getExpressionParser()->parseExpression();

        [$variables, $only, $ignoreMissing] = $this->parseArguments();

        $file = sprintf('/snippets/%s.liquid', $expr->getAttribute('value'));

        $expr->setAttribute('value', $file);

        return new IncludeNode(
            $expr,
            $variables,
            $only,
            $ignoreMissing,
            $token->getLine(),
            $this->getTag()
        );
    }

    public function getTag(): string
    {
        return 'render';
    }
}
