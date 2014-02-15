<?php
class IfParser extends BaseParser
{
    public function parse(&$tokens) {
        // consume required tokens
        $token = $this->pop($tokens);

        switch($token[0]) {
        case 'IF_OPEN':
            return $this->parseIfOpen($token, $tokens);
        case 'IF_CLOSE':
            return $this->parseIfClose();
        case 'ELSE':
            return $this->parseElse($tokens);
        case 'ELSE_IF':
            return $this->parseElseIf($token, $tokens);
        case 'HTML':
            return $this->parseHTML($token, $tokens);
        }
    }

    private function parseIfOpen($token, &$tokens) {
        $value = '<?php if(' . $token[1] . '): ?>' . "\n";
        return $value . $this->parse($tokens);
    }

    private function parseIfClose() {
        return "<?php endif; ?>\n";
    }

    private function parseElse(&$tokens) {
        return "<?php else: ?>\n" . $this->parse($tokens);
    }

    private function parseElseIf($token, &$tokens) {
        $value = '<?php else if(' . $token[1] . '): ?>' . "\n";
        return $value . $this->parse($tokens);
    }

    private function parseHTML($token, &$tokens) {
        return $token[1] . $this->parse($tokens);
    }
}
