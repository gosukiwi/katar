<?php
class ValueParser extends BaseParser
{
    public function parse(&$tokens) {
        $token = $this->pop($tokens, 'VALUE');
        return '<?php echo ' . $token[1] . '; ?>';
    }
}
