<?php
class ValueParser
{
    public function parse(&$tokens) {
        $token = array_shift($tokens);
        return '<?php echo ' . $token[1] . '; ?>';
    }
}
