<?php
class ForParser extends BaseParser
{
    public function parse(&$tokens) {
        // consume required tokens
        $for_open_token = $this->pop($tokens, 'FOR_OPEN');

        // create output so far
        $output = '<?php foreach(' . $for_open_token[1][1] . ' as ' . 
            $for_open_token[1][0] . '): ?>' . "\n";

        $expression_parser = new ExpressionParser;
        while(true) {
            list($type, $value) = $this->peek($tokens);

            if($type == 'FOR_CLOSE') {
                // pop the element, and add the value
                $this->pop($tokens);
                $output .= '<?php endfor; ?>' . "\n";
                break;
            } else {
                $output .= $expression_parser->parse($tokens);
            }
        }

        return $output;
    }
}
