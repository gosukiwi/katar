<?php
class Token
{
    public $type;
    protected $rule;

    public function __construct() {
        $this->type = 'UNDEFINED';
        $this->rule = null;
    }

    public function match($str) {
        preg_match($this->rule, $str, $matches, PREG_OFFSET_CAPTURE);

        if(count($matches) > 0) {
            return $this->parse_matches($matches);
        }

        return null;
    }

    protected function parse_matches($matches) {
        return $matches[0][0];
    }
}
