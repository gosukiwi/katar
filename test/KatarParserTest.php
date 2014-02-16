<?php
require_once(__DIR__ . '/../src/Katar.php');

class KatarParserTest extends PHPUnit_Framework_TestCase
{
    private $parser;
    private $tokenizer;

    public function setUp() {
        $this->tokenizer = new \Katar\KatarTokenizer();
        $this->parser = new \Katar\KatarParser();
        $this->parser->setTokenizer($this->tokenizer);
    }

    public function testIf() {
        $str = "@if \$a == 0\n<p>A is 0</p>\n@endif\n";
        $result = $this->compile($str);
        $this->assertEquals("<?php if(\$a == 0): ?>\n<p>A is 0</p>\n<?php endif; ?>\n", $result);

        $str = "@if \$a == 0\n<p>A is 0</p>\n@else\n<p>A is not 0</p>\n@endif\n";
        $result = $this->compile($str);
        $this->assertEquals("<?php if(\$a == 0): ?>\n<p>A is 0</p>\n<?php else: ?>\n<p>A is not 0</p>\n<?php endif; ?>\n", $result);

        $str = "@if \$a == 0\n<p>A is 0</p>\n@else if \$a == 1\n<p>A is 1</p>\n@else\n<p>A is not 0</p>\n@endif\n";
        $result = $this->compile($str);
        $this->assertEquals("<?php if(\$a == 0): ?>\n<p>A is 0</p>\n<?php elseif(\$a == 1): ?>\n<p>A is 1</p>\n<?php else: ?>\n<p>A is not 0</p>\n<?php endif; ?>\n",
            $result);

        // test several expressions
        $str = "@if \$a == 0\n<p>{{ \$a }} is 0</p>\n@endif\n";
        $result = $this->compile($str);
        $this->assertEquals("<?php if(\$a == 0): ?>\n<p><?php echo \$a; ?> is 0</p>\n<?php endif; ?>\n", $result);
    }

    public function testFor() {
        $str = "@for \$person in \$people\n<p>Hello</p>\n@endfor\n";
        $value = $this->compile($str);
        $this->assertEquals("<?php foreach(\$people as \$person): ?>\n<p>Hello</p>\n<?php endfor; ?>\n", $value);

        // test several expressions
        $str = "@for \$person in \$people\n<p>Hello</p>\nI'm {{ \$name }}\n@endfor\n";
        $value = $this->compile($str);
        $this->assertEquals("<?php foreach(\$people as \$person): ?>\n<p>Hello</p>\nI'm <?php echo \$name; ?>\n<?php endfor; ?>\n", $value);

        // test missing the @endfor
        $this->setExpectedException('Exception');
        $str = "@for \$person in \$people\n<p>Hello</p>\n";
        $value = $this->compile($str);
    }

    public function testHtml() {
        $str = '<p><?php echo "hello" . $name; ?></p>';
        $value = $this->compile($str);
        $this->assertEquals('<p><?php echo "hello" . $name; ?></p>', $value);
    }

    public function testParseValue() {
        $str = '<p>{{ $person->name }}</p>';
        $value = $this->compile($str);
        $this->assertEquals('<p><?php echo $person->name; ?></p>', $value);

        $str = '<p>{{ $name }}</p>';
        $value = $this->compile($str);
        $this->assertEquals('<p><?php echo $name; ?></p>', $value);

        $str = '<p>{{ $myobj->test() }}</p>';
        $value = $this->compile($str);
        $this->assertEquals('<p><?php echo $myobj->test(); ?></p>', $value);
    }

    public function testFilteredValue() {
        $str = '<p>{{ $person->name | strtoupper }}</p>';
        $value = $this->compile($str);
        $this->assertEquals('<p><?php echo strtoupper($person->name); ?></p>', $value);

        $str = '<p>{{ $person->name | strtoupper | trim }}</p>';
        $value = $this->compile($str);
        $this->assertEquals('<p><?php echo strtoupper(trim($person->name)); ?></p>', $value);

        // test custom filter
        $str = '<p>{{ $person->name | custom_trim }}</p>';
        $value = $this->compile($str);
        $this->assertEquals('<p><?php echo \Katar\Parsers\FilteredValueParser::filter(\'custom_trim\', $person->name); ?></p>', $value);
    }

    private function compile($str) {
        $tokens = $this->parser->compile($str);
        return $tokens;
    }
}
