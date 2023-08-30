<?php

namespace FauxLiquid\Parser;

use Twig\Error\SyntaxError;
use Twig\Node\Node;
use Twig\Node\SetNode;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

class AssignTokenParser extends AbstractTokenParser
{
    public function parse(Token $token): Node
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $names = $this->parser->getExpressionParser()->parseAssignmentExpression();

        $capture = false;
        if ($stream->nextIf(/* Token::OPERATOR_TYPE */ 8, '=')) {
            $values = $this->parser->getExpressionParser()->parseMultitargetExpression();

            $stream->expect(/* Token::BLOCK_END_TYPE */ 3);

            if (\count($names) !== \count($values)) {
                throw new SyntaxError(
                    'When using assign, you must have the same number of variables and assignments.', $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }
        } else {
            $capture = true;

            if (\count($names) > 1) {
                throw new SyntaxError('When using set with a block, you cannot have a multi-target.', $stream->getCurrent()->getLine(), $stream->getSourceContext());
            }

            $stream->expect(/* Token::BLOCK_END_TYPE */ 3);

            $values = $this->parser->subparse([$this, 'decideBlockEnd'], true);
            $stream->expect(/* Token::BLOCK_END_TYPE */ 3);
        }

        return new SetNode(
            $capture,
            $names,
            $values,
            $lineno,
            $this->getTag()
        );
    }

    public function decideBlockEnd(Token $token): bool
    {
        return $token->test('endassign');
    }

    public function getTag(): string
    {
        return 'assign';
    }
}
