<?php

namespace FauxLiquid;

use FauxLiquid\Operator\ContainsOperator;
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
        return [
            new \Twig\TwigFilter('money_without_currency', function ($money) {
                return Filters::centsToDollars($money);
            }),
            new \Twig\TwigFilter('money', function ($money) {
                return '$' . Filters::centsToDollars($money);
            }),
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
            new SchemaTokenParser(),
            new UnlessTokenParser(),
        ];
    }
}
