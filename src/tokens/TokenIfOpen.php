<?php
class TokenIfOpen extends Token
{
    public function __construct() {
        $this->type = 'IF_OPEN';
        $this->rule = '/^[\s\t]*@if\s*(.*?)\s*\n$/';
    }

    protected function parse_matches($matches) {
        return $matches[1][0];
    }
}
