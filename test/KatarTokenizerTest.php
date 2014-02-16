<?php
require_once(__DIR__ . '/../src/Katar.php');

class KatarTokenizerTest extends PHPUnit_Framework_TestCase
{
    private $tokenizer;

    public function setUp() {
        $this->tokenizer = new \Katar\Tokenizer();
    }

    public function testValue() {
        $str = '<p>{{ hello }}</p>';
        $tokens = $this->tokenizer->tokenize($str);

        $html = $tokens[0];
        $val = $tokens[1];
        $html2 = $tokens[2];

        $this->assertEquals('HTML', $html[0]);
        $this->assertEquals('<p>', $html[1]);

        $this->assertEquals('VALUE', $val[0]);
        $this->assertEquals('hello', $val[1]);

        $this->assertEquals('HTML', $html2[0]);
        $this->assertEquals('</p>', $html2[1]);

        // test another value
        $str = '{{ hello.world }}';
        $tokens = $this->tokenizer->tokenize($str);
        $this->assertEquals('hello.world', $tokens[0][1]);

        // test
        $str = '<p>{ hello }</p>';
        $tokens = $this->tokenizer->tokenize($str);
        $html = $tokens[0];
        $this->assertEquals('<p>{ hello }</p>', $html[1]);
    }

    public function testEmbeddedAt() {
        $str = "@if true\n<p>Hello my mail is some@gmail.com</p>\n@endif\n";
        $tokens = $this->tokenizer->tokenize($str);
    }

    public function testIf() {
        $str = "@if \$name > 0\n<p>hello mang</p>\n@endif\n";
        $tokens = $this->tokenizer->tokenize($str);
        $this->assertEquals('$name > 0', $tokens[0][1]);

        $str = "@if \$name > 0\n<p>hello mang</p>\n@else\n<p>byebye</p>\n@endif\n";
        $tokens = $this->tokenizer->tokenize($str);
        $else = $tokens[2];
        $endif = $tokens[4];
        $this->assertEquals('ELSE', $else[0]);
        $this->assertEquals('IF_CLOSE', $endif[0]);
    }

    public function testElseIf() {
        $str = "@if \$name > 0\n<p>hello mang</p>\n@else if \$name < 0\n<p>something else</p>\n   @endif\n";
        $tokens = $this->tokenizer->tokenize($str);
        $elseif = $tokens[2];
        $this->assertEquals('$name < 0', $elseif[1]);
    }

    public function testFor() {
        $str = "@for person in people\n";
        $tokens = $this->tokenizer->tokenize($str);
        $for_open = $tokens[0];

        $this->assertEquals('person', $for_open[1][0]);
        $this->assertEquals('people', $for_open[1][1]);

        $str = "@for person in people\n<p>this is html <3</p>\n@endfor\n";
        $tokens = $this->tokenizer->tokenize($str);

        $for_open = $tokens[0];
        $html = $tokens[1];
        $for_close = $tokens[2];

        $this->assertEquals('person', $for_open[1][0]);
        $this->assertEquals('people', $for_open[1][1]);
        $this->assertEquals("<p>this is html <3</p>\n", $html[1]);
        $this->assertEquals('', $for_close[1]);
    }

    public function testFilteredValue() {
        $str = "{{ \$name | strtoupper | other }}";
        $tokens = $this->tokenizer->tokenize($str);
        $filtered_value = $tokens[0];

        $this->assertEquals('$name', $filtered_value[1][0]);
        $this->assertEquals('strtoupper', $filtered_value[1][1]);
        $this->assertEquals('other', $filtered_value[1][2]);
    }
}
