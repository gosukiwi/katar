<?php
namespace Katar\Tokens;

class TokenElse extends Token
{
    public function __construct() {
        $this->type = 'ELSE';
        $this->rule = '/^[\s\t]*@else\s*\n$/';
    }

    protected function parse_matches($matches) {
        return '';
    }
}
