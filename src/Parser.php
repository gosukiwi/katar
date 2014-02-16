<?php
namespace Katar;

/**
 * Receives an array of tokens and translates them onto PHP
 *
 * The tokenizer is optional and can be plugged in using setTokenizer,
 * when this happens the compile method can compile from a string instead
 * of an array of tokens.
 */
class Parser
{
    private $tokenizer;
    private $expression_parser;

    public function __construct() {
        $this->tokenizer = null;
        $this->expression_parser = new Parsers\ExpressionParser;
        $this->filters = array();
    }

    public function registerFilter($name, $filter) {
        Parsers\FilteredValueParser::registerFilter($name, $filter);
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
