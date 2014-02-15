<?php
namespace Katar\Tokens;

/**
 * Defines a type name and a regular expression to match
 * all valid strings this token represents.
 */
class Token
{
    public $type;
    protected $rule;

    public function __construct() {
        $this->type = 'UNDEFINED';
        $this->rule = null;
    }

    /**
     * Matches a string with the token
     *
     * @param string $str The string to match against the token
     *
     * @return An array with the matched groups of the token REGEX or null if 
     * the string did not match the token.
     */
    public function match($str) {
        preg_match($this->rule, $str, $matches, PREG_OFFSET_CAPTURE);

        if(count($matches) > 0) {
            return $this->parse_matches($matches);
        }

        return null;
    }

    /**
     * Takes the returned groups of the regular expression match and returns
     * the values with a custom format.
     */
    protected function parse_matches($matches) {
        return $matches[0][0];
    }
}
