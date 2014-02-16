<?php
namespace Katar;

/**
 * Katar tokenizer class, used to generate an array of tokens from a string.
 *
 * The array is in the format [type, value], the type is always a string, the
 * value can change, it is either a string or an array.
 */
class Tokenizer
{
    private $tokens;

    public function __construct() {
        $this->tokens = array();

        $this->tokens[] = new Tokens\TokenFilteredValue();
        $this->tokens[] = new Tokens\TokenValue();
        $this->tokens[] = new Tokens\TokenIfOpen();
        $this->tokens[] = new Tokens\TokenElse();
        $this->tokens[] = new Tokens\TokenElseIf();
        $this->tokens[] = new Tokens\TokenIfClose();
        $this->tokens[] = new Tokens\TokenForOpen();
        $this->tokens[] = new Tokens\TokenForClose();
    }

    public function tokenize($source) {
        // just in case, add a trailing new line
        // $source .= "\n";
        $result = array();
        $str = '';
        $html = '';

        foreach(str_split($source) as $char) {
            $str .= $char;

            // ignore all html, it's basically whitespace
            if(strlen($str) == 1) {
                if($str == '@') {
                    // all @ commands must start on a new line on their own
                    $last_line = trim(end(explode("\n", $html)));
                    if(!empty($last_line)) {
                        // if it's not empty, it means there's something else
                        // on the line, just treat it as HTML
                        $str = '';
                        $html .= $char;
                        continue;
                    }
                } else if($str == '{' && substr($html, -1) == '{') {
                    $html = substr($html, 0, -1);
                    $str = '{' . $str;
                } else {
                    $str = '';
                    $html .= $char;
                    continue;
                }
            } 

            if(!empty($html)) {
                $result[] = array('HTML', $html);
                $html = '';
            }

            foreach($this->tokens as $token) {
                $value = $token->match($str);
                if(!is_null($value)) {
                    $result[] = array($token->type, $value);
                    $str = '';
                }
            }
        }

        // if we have pending HTML, add the token
        if(!empty($html)) {
            $result[] = array('HTML', $html);
        }

        return $result;
    }
}

