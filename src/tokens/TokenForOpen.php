<?php
namespace Katar\Tokens;

class TokenForOpen extends Token
{
    public function __construct() {
        $this->type = 'FOR_OPEN';
        $this->rule = '/^[\s\t]*@for\s+(.+?)\s+in\s+(.+?)\n$/';
    }

    protected function parse_matches($matches) {
        return array($matches[1][0], $matches[2][0]);
    }
}
