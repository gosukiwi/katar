<?php
/**
 * Katar tokenizer class, used to generate an array of tokens from a string.
 *
 * The array is in the format [type, value], the type is always a string, the
 * value can change, it is either a string or an array.
 */
class KatarTokenizer
{
    private $tokens;

    public function __construct() {
        $this->tokens = array();

        $this->tokens[] = new TokenFilteredValue();
        $this->tokens[] = new TokenValue();
        $this->tokens[] = new TokenIfOpen();
        $this->tokens[] = new TokenElse();
        $this->tokens[] = new TokenElseIf();
        $this->tokens[] = new TokenIfClose();
        $this->tokens[] = new TokenForOpen();
        $this->tokens[] = new TokenForClose();
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
            if(strlen($str) == 1 && $str != '{' && $str != '@') {
                $str = '';
                $html .= $char;
                continue;
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


