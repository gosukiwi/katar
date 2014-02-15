<?php
class TokenElseIf extends Token
{
    public function __construct() {
        $this->type = 'ELSE_IF';
        $this->rule = '/^[\s\t]*@else\s+if\s*(.*?)\s*\n$/';
    }

    protected function parse_matches($matches) {
        return $matches[1][0];
    }
}
