<?php
require_once(__DIR__ . '/../src/Katar.php');

class KatarParserTest extends PHPUnit_Framework_TestCase
{
    private $parser;
    private $tokenizer;

    public function setUp() {
        $this->tokenizer = new \Katar\Tokenizer();
        $this->parser = new \Katar\Parser();
        $this->parser->setTokenizer($this->tokenizer);
    }

    public function testEscape() {
        $str = "I'm html {> {{escape}} this <}.";
        $result = $this->compile($str);
        $this->assertEquals(
            "\$output .= 'I&#039;m html ';\n" .
            "\$output .= ' {{escape}} this ';\n" . 
            "\$output .= '.';\n",
            $result);
    }

    public function testIf() {
        $str = "@if \$a == 0\nHI!\n@endif\n";
        $result = $this->compile($str);
        $this->assertEquals("if(\$a == 0) {\n\$output .= 'HI!\n';\n}\n", 
            $result);

        $str = "@if \$a == 0\nIf Case\n@else\nElse Case\n" .
            "@endif\n";
        $result = $this->compile($str);
        $this->assertEquals("if(\$a == 0) {\n\$output .= 'If Case\n';\n} else ".
            "{\n\$output .= 'Else Case\n';\n}\n", $result);

        $str = "@if \$a == 0\nIf Case\n@else if \$a < 0\nElseIf Case\n" .
            "@else\nElse Case\n@endif\n";
        $result = $this->compile($str);
        $this->assertEquals("if(\$a == 0) {\n\$output .= 'If Case\n';\n} else"
            . "if(\$a < 0) {\n\$output .= 'ElseIf Case\n';\n} else {\n" .
            "\$output .= 'Else Case\n';\n}\n", $result);

        // test several expressions
        $str = "@if \$a == 0\n<p>{{ \$a }} is 0</p>\n@endif\n";
        $result = $this->compile($str);
        $this->assertEquals("if(\$a == 0) {\n\$output .= '&lt;p&gt;';\n" . 
            "\$output .= \$a;\n\$output .= ' is 0&lt;/p&gt;\n';\n}\n",
            $result);
    }

    public function testFor() {
        $str = "@for \$person in \$people\n<p>Hello</p>\n@endfor\n";
        $value = $this->compile($str);
        $this->assertEquals(
            "\$for_index = 0; foreach(\$people as \$person) {\n".
            "\$output .= '&lt;p&gt;Hello&lt;/p&gt;\n';\n" .
            "\$for_index++; }\n",
            $value);

        // test several expressions
        $str = "@for \$person in \$people\n<p>Hello</p>\nI'm {{ \$name }}\n@endfor\n";
        $value = $this->compile($str);
        $this->assertEquals(
            "\$for_index = 0; foreach(\$people as \$person) {\n".
            "\$output .= '&lt;p&gt;Hello&lt;/p&gt;\nI&#039;m ';\n" .
            "\$output .= \$name;\n" .
            "\$output .= '\n';\n" .
            "\$for_index++; }\n",
            $value);

        // test missing the @endfor
        $this->setExpectedException('Exception');
        $str = "@for \$person in \$people\n<p>Hello</p>\n";
        $value = $this->compile($str);
    }

    public function testHtml() {
        $str = 'Hello';
        $value = $this->compile($str);
        $this->assertEquals("\$output .= 'Hello';\n",
            $value);
    }

    public function testParseValue() {
        $str = '<p>{{ $person->name }}</p>';
        $value = $this->compile($str);
        $this->assertEquals(
            "\$output .= '&lt;p&gt;';\n".
            "\$output .= \$person->name;\n".
            "\$output .= '&lt;/p&gt;';\n",
            $value);

        $str = '<p>{{ $name }}</p>';
        $value = $this->compile($str);
        $this->assertequals(
            "\$output .= '&lt;p&gt;';\n".
            "\$output .= \$name;\n".
            "\$output .= '&lt;/p&gt;';\n",
            $value);

        $str = '<p>{{ $myobj->test() }}</p>';
        $value = $this->compile($str);
        $this->assertequals(
            "\$output .= '&lt;p&gt;';\n".
            "\$output .= \$myobj->test();\n".
            "\$output .= '&lt;/p&gt;';\n",
            $value);
    }

    public function testFilteredValue() {
        $str = '<p>{{ $person->name | strtoupper }}</p>';
        $value = $this->compile($str);
        $this->assertequals(
            "\$output .= '&lt;p&gt;';\n".
            "\$output .= strtoupper(\$person->name);\n".
            "\$output .= '&lt;/p&gt;';\n",
            $value);

        $str = '<p>{{ $person->name | strtoupper | trim }}</p>';
        $value = $this->compile($str);
        $this->assertequals(
            "\$output .= '&lt;p&gt;';\n".
            "\$output .= strtoupper(trim(\$person->name));\n".
            "\$output .= '&lt;/p&gt;';\n",
            $value);

        // test custom filter
        $str = '<p>{{ $person->name | custom_trim }}</p>';
        $value = $this->compile($str);
        $this->assertequals(
            "\$output .= '&lt;p&gt;';\n".
            "\$output .= \Katar\Parser::filter('custom_trim', " .
                "\$person->name);\n".
            "\$output .= '&lt;/p&gt;';\n",
            $value);
    }

    private function compile($str) {
        $tokens = $this->parser->compile($str);
        return $tokens;
    }
}
