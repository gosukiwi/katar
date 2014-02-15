<?php
class ForParser
{
    public function parse(&$tokens) {
        // consume required tokens
        $for_open_token = array_shift($tokens);
        $html_token = array_shift($tokens);
        $for_close_token = array_shift($tokens);

        if(@$html_token[0] !== 'HTML') {
            throw new Exception('Expected HTML token, got ' . @$html_token[0]);
        }

        if(@$for_close_token[0] !== 'FOR_CLOSE') {
            throw new Exception('Expected FOR_CLOSE token, got ' . @$for_close_token[0]);
        }

        // translate for_open token
        $value = '<?php foreach(' . $for_open_token[1][1] . ' as ' . $for_open_token[1][0] . '): ?>' . "\n";

        // translate html token, as it's simple enough just display
        // the value, for better performance
        $value .= $html_token[1];

        // translate for_close token
        $value .= '<?php endfor; ?>' . "\n";

        return $value;
    }
}
