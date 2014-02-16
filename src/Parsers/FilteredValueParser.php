<?php
namespace Katar\Parsers;

class FilteredValueParser extends BaseParser
{
    public function parse(&$tokens) {
        list($type, $filters) = $this->pop($tokens, 'FILTERED_VALUE');
        $value = array_shift($filters);
        $opening = '';
        $closing = '';

        foreach($filters as $filter) {
            $opening .= $filter . '(';
            $closing .= ')';
        }

        return '<?php echo ' . $opening . $value . $closing . '; ?>';
    }
}
