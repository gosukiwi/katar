<?php
class HTMLParser
{
    public function parse(&$tokens) {
        $token = array_shift($tokens);
        return $token[1];
    }
}
