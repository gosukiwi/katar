<?php
namespace Katar\Parsers;

/**
 * An expression is either
 *  HTML
 *  An IF
 *  A FOR
 *  A VALUE
 */
class ExpressionParser extends BaseParser
{
    public function parse(&$tokens) {
        // check first token
        $token = $this->peek($tokens);
        $type = $token[0];

        $for_parser = new ForParser;
        $if_parser = new IfParser;
        $value_parser = new ValueParser;
        $filtered_value_parser = new FilteredValueParser;
        $html_parser = new HTMLParser;
        $escape_parser = new EscapeParser;

        switch($type) {
        case 'IF_OPEN':
            return $if_parser->parse($tokens);
        case 'FOR_OPEN':
            return $for_parser->parse($tokens);
        case 'FILTERED_VALUE':
            return $filtered_value_parser->parse($tokens);
        case 'VALUE':
            return $value_parser->parse($tokens);
        case 'HTML':
            return $html_parser->parse($tokens);
        case 'ESCAPE':
            return $escape_parser->parse($tokens);
        default:
            throw new \Exception("Could not parse expression, invalid token '$type'");
        }
    }
}
