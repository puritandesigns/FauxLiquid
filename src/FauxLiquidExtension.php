<?php

namespace FauxLiquid;

use FauxLiquid\Operator\ContainsOperator;
use FauxLiquid\Parser\AssignTokenParser;
use FauxLiquid\Parser\CommentTokenParser;
use FauxLiquid\Parser\IfTokenParser;
use FauxLiquid\Parser\RenderTokenParser;
use FauxLiquid\Parser\SchemaTokenParser;
use FauxLiquid\Parser\UnlessTokenParser;
use FauxLiquid\Support\Filters;
use Twig\ExpressionParser;
use Twig\Extension\AbstractExtension;

class FauxLiquidExtension extends AbstractExtension
{
    public function getName()
    {
        return 'FauxLiquid';
    }

    public function getFilters()
    {
        $money_with_currency = function ($money) {
            return '$' . Filters::centsToDollars($money);
        };

        return [
            new \Twig\TwigFilter('money_without_currency', function ($money) {
                return Filters::centsToDollars($money);
            }),
            new \Twig\TwigFilter('money_with_currency', $money_with_currency),
            new \Twig\TwigFilter('money', $money_with_currency),
        ];
    }

    public function getOperators()
    {
        return [
            [], // unary operators
            [ // binary operators
                'contains' => [
                    'class' => ContainsOperator::class,
                    'precedence' => 20,
                    'associativity' => ExpressionParser::OPERATOR_RIGHT,
                ]
            ]
        ];
    }

    public function getTokenParsers()
    {
        return [
            new AssignTokenParser(),
            new CommentTokenParser(),
            new IfTokenParser(),
            new RenderTokenParser(),
            new SchemaTokenParser(),
            new UnlessTokenParser(),
        ];
    }
}
