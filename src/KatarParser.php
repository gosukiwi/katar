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
    private $expression_parser;

    public function __construct() {
        $this->tokenizer = null;
        $this->expression_parser = new ExpressionParser;
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
        // as the token array is passed by reference
        $output = '';
        while(!empty($tokens)) {
            $output .= $this->expression_parser->parse($tokens);
        }

        return $output;
    }
}
