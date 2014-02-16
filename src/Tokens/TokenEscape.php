<?php
namespace Katar\Tokens;

class TokenEscape extends Token
{
    public function __construct() {
        $this->type = 'ESCAPE';
        $this->rule = '/^{>(.*?)<}$/';
    }

    protected function parse_matches($matches) {
        return $matches[1][0];
    }
}
