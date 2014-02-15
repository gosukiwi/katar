<?php
/**
 * Receives an array of tokens and translates them onto PHP
 *
 * The language translates line by line to PHP so we avoid creating a syntax
 * tree, for now, each tokens matches 1 to 1 with an expression.
 */
class KatarParser
{
    private $tokenizer;
    private $parsers;

    public function __construct() {
        $this->tokenizer = null;
        $this->parsers = array();

        $this->parsers['HTML'] = new HTMLParser;
        $this->parsers['VALUE'] = new ValueParser;
        $this->parsers['FOR_OPEN'] = new ForParser;
        $this->parsers['IF_OPEN'] = new IfParser;
    }

    public function setTokenizer($tokenizer) {
        $this->tokenizer = $tokenizer;
    }

    public function compile($tokens) {
        // if we get an string, tokenize first
        if(is_string($tokens)) {
            if(!$this->tokenizer) {
                throw new Exception("Tokenizer not bound.");
            }

            $tokens = $this->tokenizer->tokenize($tokens);
        }

        // parse tokens! let each parser consume as much as wanted 
        $output = '';
        while(!empty($tokens)) {
            $output .= $this->parse($tokens);
        }

        return $output;
    }

    private function parse(&$tokens) {
        $type = $tokens[0][0];
        if(!array_key_exists($type, $this->parsers)) {
            throw new Exception("Invalid token: $type");
        }

        $parser = $this->parsers[$type];
        return $parser->parse($tokens);
    }
}
