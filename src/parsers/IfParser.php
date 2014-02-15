<?php
class IfParser extends BaseParser
{
    public function parse(&$tokens) {
        // consume required tokens
        $if_open = $this->pop($tokens, 'IF_OPEN');
        $output = '<?php if(' . $if_open[1] . '): ?>' . "\n";

        $expression_parser = new ExpressionParser;
        $seeking = true;
         while($seeking) {
            list($type, $value) = $this->peek($tokens);

            switch($type) {
            case 'IF_CLOSE':
                $this->pop($tokens);
                $output .= "<?php endif; ?>\n";
                $seeking = false;
                break;
            case 'ELSE':
                $this->pop($tokens);
                $output .= "<?php else: ?>\n";
                break;
            case 'ELSE_IF':
                $token = $this->pop($tokens);
                $output .= "<?php elseif(" . $token[1] . "): ?>\n";
                break;
            default:
                $output .= $expression_parser->parse($tokens);
                break;
            }
        }

        return $output;
    }
}
