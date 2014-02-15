<?php
class TokenForClose extends Token
{
    public function __construct() {
        $this->type = 'FOR_CLOSE';
        $this->rule = '/^[\s\t]*@endfor\s*\n$/';
    }

    protected function parse_matches($matches) {
        return '';
    }
}
