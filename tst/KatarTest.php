<?php
require_once(__DIR__ . '/../src/Katar.php');

class KatarParserTest extends PHPUnit_Framework_TestCase
{
    private $katar;

    public function setUp() {
        $this->katar = new Katar(__DIR__ . '/cache');
    }

    public function testCompile() {
        $file = __DIR__ . '/katar/test1.katar';
        $this->katar->compile($file, false);

        $cache = file_get_contents(__DIR__ . '/cache/' . md5($file));
        $precompiled = file_get_contents(__DIR__ . '/compiled/test1.php');
        $this->assertEquals($cache, $precompiled);
    }
}
