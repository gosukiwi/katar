<?php
class BaseParser
{
    protected function pop(&$array, $expected = null) {
        if(count($array) == 0) {
            $message = is_null($expected)
                ? "Ran out of tokens, expected $expected."
                : 'Ran out of tokens.';

            throw new Exception($message);
        }

        $token = array_shift($array);
        $type = $token[0];

        if(!is_null($expected) && $type != $expected) {
            throw new Exception("Invalid token, got $type, expected $expected.");
        }

        return $token;
    }
}
