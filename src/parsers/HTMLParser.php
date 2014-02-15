<?php
class HTMLParser extends BaseParser
{
    public function parse(&$tokens) {
        $token = $this->pop($tokens, 'HTML');
        return $token[1];
    }
}
