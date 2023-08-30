<?php

namespace FauxLiquid\Parser;

use Twig\Error\SyntaxError;
use Twig\Node\IfNode;
use Twig\Node\Node;
use Twig\Token;

class IfTokenParser extends \Twig\TokenParser\AbstractTokenParser
{
    public function parse(Token $token): Node
    {
        $lineno = $token->getLine();
        $expr = $this->parser->getExpressionParser()->parseExpression();
        $stream = $this->parser->getStream();
        $stream->expect(/* Token::BLOCK_END_TYPE */ 3);
        $body = $this->parser->subparse([$this, 'decideIfFork']);
        $tests = [$expr, $body];
        $else = null;

        $end = false;
        while (!$end) {
            switch ($stream->next()->getValue()) {
                case 'else':
                    $stream->expect(/* Token::BLOCK_END_TYPE */ 3);
                    $else = $this->parser->subparse([$this, 'decideIfEnd']);
                    break;

                case 'elseif':
                    $expr = $this->parser->getExpressionParser()->parseExpression();
                    $stream->expect(/* Token::BLOCK_END_TYPE */ 3);
                    $body = $this->parser->subparse([$this, 'decideIfFork']);
                    $tests[] = $expr;
                    $tests[] = $body;
                    break;

                case 'elsif':
                    $expr = $this->parser->getExpressionParser()->parseExpression();
                    $stream->expect(/* Token::BLOCK_END_TYPE */ 3);
                    $body = $this->parser->subparse([$this, 'decideIfFork']);
                    $tests[] = $expr;
                    $tests[] = $body;
                    break;

                case 'endif':
                    $end = true;
                    break;

                default:
                    throw new SyntaxError(sprintf('Unexpected end of template. Twig was looking for the following tags "else", "elsif", or "endif" to close the "if" block started at line %d).', $lineno), $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }
        }

        $stream->expect(/* Token::BLOCK_END_TYPE */ 3);

        return new IfNode(
            new Node($tests),
            $else,
            $lineno,
            $this->getTag()
        );
    }

    public function decideIfFork(Token $token): bool
    {
        return $token->test(
            ['elseif', 'elsif', 'else', 'endif']
        );
    }

    public function decideIfEnd(Token $token): bool
    {
        return $token->test(['endif']);
    }

    public function getTag(): string
    {
        return 'if';
    }
}
