<?php
namespace Katar\Tokens;

class TokenIfClose extends Token
{
    public function __construct() {
        $this->type = 'IF_CLOSE';
        $this->rule = '/^[\s\t]*@endif\s*\n$/';
    }

    protected function parse_matches($matches) {
        return '';
    }
}
