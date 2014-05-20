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
    private $tokenList;

    public function __construct() {
        $this->tokenList = array(
            // IF_OPEN token
            array(
                'IF_OPEN',
                '/^[\s\t]*@if\s*(.*?)\s*\n$/',
                function ($matches) {
                    return $matches[1][0];
                }
            ),
            // ELSE token
            array(
                'ELSE',
                '/^[\s\t]*@else\s*\n$/',
                function ($matches) {
                    return '';
                }
            ),
            // ELSE_IF token
            array(
                'ELSE_IF',
                '/^[\s\t]*@else\s+if\s*(.*?)\s*\n$/',
                function ($matches) {
                    return $matches[1][0];
                }
            ),
            // IF_CLOSE token
            array(
                'IF_CLOSE',
                '/^[\s\t]*@endif\s*\n$/',
                function ($matches) {
                    return '';
                }
            ),
            // FILTERED_VALUE token
            array(
                'FILTERED_VALUE',
                '/{{\s*(.+?)(\s*\|\s*([a-zA-Z_]+)\s*)+}}/',
                function ($matches) {
                    $str = array_shift(str_replace(array('{{', '}}'), array('', ''), $matches[0]));
                    $output = array();
                    foreach(explode('|', $str) as $filter) {
                        $output[] = trim($filter);
                    }
                    return $output;
                }
            ),
            // VALUE token
            array(
                'VALUE',
                '/^{{\s*(.*?)\s*}}$/',
                function ($matches) {
                    return $matches[1][0];
                }
            ),
            // FOR_OPEN token
            array(
                'FOR_OPEN',
                '/^[\s\t]*@for\s+(.+?)\s+in\s+(.+?)\n$/',
                function ($matches) {
                    return array($matches[1][0], $matches[2][0]);
                }
            ),
            // FOR_CLOSE token
            array(
                'FOR_CLOSE',
                '/^[\s\t]*@endfor\s*\n$/',
                function ($matches) {
                    return '';
                }
            ),
            // ESCAPE token
            array(
                'ESCAPE',
                '/^{>(.*?)<}$/',
                function ($matches) {
                    return $matches[1][0];
                }
            ),
        );
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
                    // match @ directive
                    // all directives must start on a new line on their own
                    $last_line = trim(end(explode("\n", $html)));
                    if(!empty($last_line)) {
                        // if it's not empty, it means there's something else
                        // on the line, just treat it as HTML
                        $str = '';
                        $html .= $char;
                        continue;
                    }
                } else if($str == '>' && substr($html, -1) == '{') {
                    // match escape
                    $html = substr($html, 0, -1);
                    $str = '{' . $str;
                } else if($str == '{' && substr($html, -1) == '{') {
                    // match value
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

            foreach($this->tokenList as $token) {
                $value = $this->match($token, $str);
                if(!is_null($value)) {
                    $result[] = array($token[0], $value);
                    $str = '';
                    break;
                }
            }
        }

        // if we have pending HTML, add the token
        if(!empty($html)) {
            $result[] = array('HTML', $html);
        }

        return $result;
    }
    
    private function match($token, $str) {
        // Try match
        $matches = null;
        preg_match($token[1], $str, $matches, PREG_OFFSET_CAPTURE);

        // Return matches or null if none
        if(count($matches) > 0) {
            return $token[2]($matches);
        }

        return null;
    }
}

