<?php
namespace Katar\Tokens;

class TokenValue extends Token
{
    public function __construct() {
        $this->type = 'VALUE';
        $this->rule = '/{{\s*(.*?)\s*}}/';
    }

    protected function parse_matches($matches) {
        return $matches[1][0];
    }
}
