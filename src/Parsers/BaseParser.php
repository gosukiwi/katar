<?php
namespace Katar\Parsers;

/**
 * A parser which translates an array of tokens onto PHP
 */
class BaseParser
{
    /**
     * Pops the first element from the array, if $expected is specified it
     * also checks for a match, throwing an exception if the matching fails.
     *
     * @param array $array The token array
     * @param string $expected The expected type of the token
     *
     * @return array The first token of the array/queue
     */
    protected function pop(&$array, $expected = null) {
        if(count($array) == 0) {
            $message = is_null($expected)
                ? "Ran out of tokens, expected $expected."
                : 'Ran out of tokens.';

            throw new \Exception($message);
        }

        $token = array_shift($array);
        $type = $token[0];

        if(!is_null($expected) && $type != $expected) {
            throw new \Exception("Invalid token, got $type, expected $expected.");
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
    protected function peek(&$array) {
        if(count($array) == 0) {
            throw new \Exception('Tokens array is empty, cannot peek');
        }

        return $array[0];
    }

    /**
     * Parses an array of tokens
     *
     * @param array $tokens An array of tokens, passed by reference
     *
     * @return string compiled PHP code
     */
    public function parse(&$tokens) {
        $token = $this->pop($tokens);
        return $token[1];
    }
}
