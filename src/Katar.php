<?php
class Katar
{
    public static function autoload($class) {
        if($class == 'KatarTokenizer') {
            require_once(__DIR__ . '/KatarTokenizer.php');
        } else if($class == 'KatarParser') {
            require_once(__DIR__ . '/KatarParser.php');
        }

        $folders = array('tokens', 'parsers');

        foreach($folders as $folder) {
            $file = __DIR__ . '/' . $folder . '/' . $class . '.php';
            if(file_exists($file)) {
                require_once($file);
            }
        }
    }

    private $views_cache;
    private $parser;

    public function __construct($views_cache) {
        $this->views_cache = $views_cache;

        $tokenizer = new KatarTokenizer();
        $this->parser = new KatarParser();
        $this->parser->setTokenizer($tokenizer);
    }

    public function setTokenizer($tokenizer) {
        $this->parser->setTokenizer($tokenizer);
    }

    public function compile($file, $include_file = true) {
        if(!file_exists($file)) {
            throw new Exception("Could not compile $file, file not found");
        }

        $source = file_get_contents($file);
        $source_update = filemtime($file);

        $cache_file = $this->views_cache . '/' . md5($file);
        $compiled = null;

        if(!file_exists($cache_file) || filemtime($cache_file) < filemtime($file)) {
            $compiled = $this->parser->compile($source);
            file_put_contents($cache_file, $compiled);
        }

        if($include_file) {
            require($cache_file);
        } else {
            return $compiled;
        }
    }
}

spl_autoload_register(__NAMESPACE__ .'\Katar::autoload');

