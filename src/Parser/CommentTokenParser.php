<?php

namespace FauxLiquid\Parser;

use Twig\TokenParser\AbstractTokenParser;

class CommentTokenParser extends AbstractTokenParser
{
    public function parse(\Twig\Token $token)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();

        $stream->expect(\Twig\Token::BLOCK_END_TYPE);

        while ($stream->next()->getValue() != 'endcomment') {
            // ignoring contents of comment
        }

        $stream->expect(\Twig\Token::BLOCK_END_TYPE);

        return new \Twig\Node\Node([]);
    }

    public function getTag()
    {
        return 'comment';
    }
}
