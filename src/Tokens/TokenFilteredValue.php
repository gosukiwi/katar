<?php
namespace Katar\Tokens;

class TokenFilteredValue extends Token
{
    public function __construct() {
        $this->type = 'FILTERED_VALUE';
        $this->rule = '/{{\s*(.+?)(\s*\|\s*([a-zA-Z_]+)\s*)+}}/';
    }

    protected function parse_matches($matches) {
        $str = array_shift(str_replace(array('{{', '}}'), array('', ''), $matches[0]));
        $output = array();
        foreach(explode('|', $str) as $filter) {
            $output[] = trim($filter);
        }
        return $output;
    }
}
