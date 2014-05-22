<?php
require_once(__DIR__ . '/../src/Katar.php');

class KatarTest extends PHPUnit_Framework_TestCase
{
    private $katar;

    public function setUp() {
        @mkdir(__DIR__ . '/cache');
        $this->katar = new \Katar\Katar(__DIR__ . '/katar', 
            __DIR__ . '/cache');
    }

    public function tearDown() {
        $this->rmdir(__DIR__ . '/cache');
    }

    /*public function testCompile() {
        $file = 'test1.katar';
        $this->katar->compile($file);

        $cache = file_get_contents(__DIR__ . '/cache/' . md5($file));
        $precompiled = file_get_contents(__DIR__ . '/compiled/test1.php');

        $this->assertEquals($precompiled, $cache);
    }*/

    public function testRender() {
        $result = $this->katar->render('test1.katar', array(
            'people' => array(
                array('name' => 'Mike'),
            ),
            'age' => 22,
            'name' => 'John',
        ));

        // TODO
        //$this->assertEquals('', $result);
    }

    /*public function testCustomFilter() {
        // this one is hard to test... TODO
        require(__DIR__ . '/filters/ExampleFilter.php');
        $cf = new ExampleFilter();
        $this->katar->registerFilter('custom_trim', array($cf, 'custom_trim'));

        $compiled = $this->katar->compile(__DIR__ . 
            '/katar/testCustomFilter.katar', array('name' => 'Mike'), false);
    }*/

    private function rmdir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }

        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }

        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->rmdir($file);
            } else {
                unlink($file);
            }
        }

        rmdir($dirPath);
    }
}

