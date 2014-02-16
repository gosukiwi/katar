<?php
namespace Katar\Parsers;

class EscapeParser extends BaseParser
{
    public function parse(&$tokens) {
        $token = $this->pop($tokens, 'ESCAPE');
        return $token[1];
    }
}
