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
    private $tokens;
    private $currLine;

    public function __construct() {
        $this->tokenizer = null;
        $this->tokens = array();
    }

    public function setTokenizer($tokenizer) {
        $this->tokenizer = $tokenizer;
    }

    public function compile($tokens) {
        $this->currLine = 0;

        // if we get an string, tokenize first
        if(is_string($tokens)) {
            if(!$this->tokenizer) {
                throw new \Exception("Tokenizer not found.");
            }

            $tokens = $this->tokenizer->tokenize($tokens);
        }

        $this->tokens = $tokens;

        // parse tokens! let each parser consume as much as wanted 
        // as the token array is passed by reference
        $output = '';
        while(!empty($this->tokens)) {
            $output .= $this->parseExpression();
        }

        return $output;
    }

    /**
     * Pops the first element from the array, if $expected is specified it
     * also checks for a match, throwing an exception if the matching fails.
     *
     * @param array $array The token array
     * @param string $expected The expected type of the token
     *
     * @return array The first token of the array/queue
     */
    private function pop($expected = null) {
        if(count($this->tokens) == 0) {
            $message = is_null($expected)
                ? "Ran out of tokens, expected $expected."
                : 'Ran out of tokens.';

            throw new SyntaxErrorException($message);
        }

        $token = array_shift($this->tokens);
        $type = $token[0];

        if(!is_null($expected) && $type != $expected) {
            throw new SyntaxErrorException("Invalid token: Got $type, expected 
                $expected.");
        }

        return $token;
    }

    /**
     * Peeks at the first element in the array/stack
     *
     * @param array The token array
     *
     * @return array The first token in the array/stack without removing it
     */
    private function peek() {
        if(count($this->tokens) == 0) {
            throw new \Exception("Unexpected end of input at line 
                $this->currLine .");
        }

        return $this->tokens[0];
    }

    /**
     * Parses an IF expression
     */
    private function parseIf() {
        // consume required tokens
        $if_open = $this->pop('IF_OPEN');
        $output = 'if(' . $if_open[1] . ') {' . "\n";
        $this->currLine++;

        $seeking = true;
        while($seeking) {
            list($type, $value) = $this->peek();

            switch($type) {
            case 'IF_CLOSE':
                $this->pop();
                $output .= "}\n";
                $seeking = false;
                $this->currLine++;
                break;
            case 'ELSE':
                $this->pop();
                $output .= "} else {\n";
                $this->currLine++;
                break;
            case 'ELSE_IF':
                $token = $this->pop();
                $output .= '} elseif(' . $token[1] . ") {\n";
                $this->currLine++;
                break;
            default:
                $output .= $this->parseExpression();
                break;
            }
        }

        return $output;
    }

    /**
     * Parses an EXPRESSION
     *
     * Expression: (IF_OPEN | FOR_OPEN | FILTERED_VALUE | VALUE | HTML 
     *  | ESCAPE)
     */
    public function parseExpression() {
        $token = $this->peek();
        // check first token
        $type = $token[0];

        switch($type) {
        case 'IF_OPEN':
            return $this->parseIf();
        case 'FOR_OPEN':
            return $this->parseFor();
        case 'FILTERED_VALUE':
            return $this->parseFilteredValue();
        case 'VALUE':
            return $this->parseValue();
        case 'HTML':
            return $this->parseHTML();
        case 'ESCAPE':
            return $this->parseEscape();
        case 'INCLUDE':
            return $this->parseInclude();
        default:
            throw new SyntaxErrorException(
                "Could not parse expression, invalid token '$type'");
        }
    }

    /**
     * Parses an HTML Expression
     */
    public function parseHTML() {
        $token = $this->pop('HTML');
        $value = $this->stripQuotes($token[1]);
        $this->currLine += substr_count($value, "\n");
        return '$output .= \'' . $value . "';\n";
    }

    /**
     * Parses a VALUE expression
     */
    public function parseValue() {
        $token = $this->pop('VALUE');
        return '$output .= ' . $token[1] . ";\n";
    }

    /**
     * Parses a FOR expression
     */
    public function parseFor() {
        // consume required tokens
        $for_open_token = $this->pop('FOR_OPEN');
        $this->currLine++;

        // create output so far
        $output = '$for_index = 0; foreach(' . 
            $for_open_token[1][1] . ' as ' . 
            $for_open_token[1][0] . ') {' . "\n";

        while(true) {
            list($type, $value) = $this->peek();

            if($type == 'FOR_CLOSE') {
                // pop the element, and add the value
                $this->pop();
                $output .= '$for_index++; }' . "\n";
                $this->currLine++;
                break;
            } else {
                $output .= $this->parseExpression();
            }
        }

        return $output;
    }

    /**
     * Parses an ESCAPE expression
     */
    public function parseEscape() {
        $token = $this->pop('ESCAPE');
        $value = $this->stripQuotes($token[1]);
        $this->currLine += substr_count($value, "\n");
        return '$output .= \'' . $value . "';\n";
    }

    /**
     * Parses a FILTERED_VALUE expression
     */
    public function parseFilteredValue() {
        list($type, $filters) = $this->pop('FILTERED_VALUE');
        $value = array_shift($filters);
        $opening = '';
        $closing = '';

        foreach($filters as $filter) {
            if(function_exists($filter)) {
                $opening .= $filter . '(';
                $closing .= ')';
            } else {
                $opening .= '\Katar\Katar::getInstance()->filter(\'' .
                    $filter . '\', ';
                $closing .= ')';
            }
        }

        return '$output .= ' . $opening . $value . $closing . ";\n";
    }

    public function parseInclude() {
        $token = $this->pop('INCLUDE');
        $file = $token[1];
        $this->currLine++;

        // this will later be replaced by Katar to the evaluated
        // result of the compiled $file
        // pretty much like a compiler directive
        return "\$output .= \Katar\Katar::getInstance()->render($file,"
           . " \$args);\n";
    }

    private function stripQuotes($str) {
        return str_replace(array(
            '"',
            "'",
        ), array(
            '&quot;',
            '&#039;'
        ), $str);
    }
}

class SyntaxErrorException extends \Exception {};
class InvalidFilterException extends \Exception {};
