<?php
namespace Katar\Parsers;

class FilteredValueParser extends BaseParser
{
    public static $filters = array();

    public static function registerFilter($name, $filter) {
        self::$filters[$name] = $filter;
    }

    public static function filter($name, $arg) {
        if(!array_key_exists($name, self::$filters)) {
            throw new \Exception("Filter $filter could not be found");
        }

        return call_user_func_array(self::$filters[$name], array($arg));
    }

    public function parse(&$tokens) {
        list($type, $filters) = $this->pop($tokens, 'FILTERED_VALUE');
        $value = array_shift($filters);
        $opening = '';
        $closing = '';

        foreach($filters as $filter) {
            if(function_exists($filter)) {
                $opening .= $filter . '(';
                $closing .= ')';
            } else {
                $opening .= '\Katar\Parsers\FilteredValueParser::filter(\'' . $filter . '\', ';
                $closing .= ')';
            }
        }

        return '<?php echo ' . $opening . $value . $closing . '; ?>';
    }
}
