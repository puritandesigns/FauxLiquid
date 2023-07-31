<?php

namespace FauxLiquid\Parser;

/**
 * Ignores a Liquid schema block
 */
class SchemaTokenParser extends \Twig\TokenParser\AbstractTokenParser
{
    public function parse(\Twig\Token $token)
    {
        $parser = $this->parser;
        $stream = $parser->getStream();

        $stream->expect(\Twig\Token::BLOCK_END_TYPE);

        $json = $stream->next()->getValue();

        if (strpos($json, '{') !== 0) {
            // should be json in the schema block...
        }

        $stream->expect(\Twig\Token::BLOCK_START_TYPE);

        $value = $stream->next()->getValue();
        if ('endschema' !== $value) {
            // should be the endschema block...
        }

        $stream->expect(\Twig\Token::BLOCK_END_TYPE);

        return new \Twig\Node\Node([]);
    }

    public function getTag()
    {
        return 'schema';
    }
}
