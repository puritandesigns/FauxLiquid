<?php

namespace FauxLiquid\Parser;

use FauxLiquid\Node\UnlessNode;
use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Token;

class UnlessTokenParser extends \Twig\TokenParser\AbstractTokenParser
{
    public function parse(Token $token): Node
    {
        $lineno = $token->getLine();
        $expr = $this->parser->getExpressionParser()->parseExpression();
        $stream = $this->parser->getStream();
        $stream->expect(/* Token::BLOCK_END_TYPE */ 3);
        $body = $this->parser->subparse([$this, 'decideUnlessFork']);
        $tests = [$expr, $body];
        $else = null;

        $end = false;
        while (!$end) {
            switch ($stream->next()->getValue()) {
                case 'else':
                    $stream->expect(/* Token::BLOCK_END_TYPE */ 3);
                    $else = $this->parser->subparse([$this, 'decideUnlessEnd']);
                    break;

                case 'elseunless':
                    $expr = $this->parser->getExpressionParser()->parseExpression();
                    $stream->expect(/* Token::BLOCK_END_TYPE */ 3);
                    $body = $this->parser->subparse([$this, 'decideUnlessFork']);
                    $tests[] = $expr;
                    $tests[] = $body;
                    break;

                case 'endunless':
                    $end = true;
                    break;

                default:
                    throw new SyntaxError(sprintf('Unexpected end of template. Twig was looking for the following tags "else", "elseunless", or "endunless" to close the "unless" block started at line %d).', $lineno), $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }
        }

        $stream->expect(/* Token::BLOCK_END_TYPE */ 3);

        return new UnlessNode(
            new Node($tests),
            $else,
            $lineno,
            $this->getTag()
        );
    }

    public function decideUnlessFork(\Twig\Token $token): bool
    {
        return $token->test(['elseunless', 'else', 'endunless']);
    }

    public function decideUnlessEnd(\Twig\Token $token): bool
    {
        return $token->test(['endunless']);
    }

    public function getTag(): string
    {
        return 'unless';
    }
}
