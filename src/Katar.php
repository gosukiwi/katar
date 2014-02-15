<?php
/**
 * Katar is a simple template engine for PHP with a clean syntax which doesn't
 * get in your way.
 *
 * @author Federico Ramirez <fedra.arg@gmail.com>
 * @licence MIT
 */

namespace Katar;

/**
 * Main Katar class, autoloads all other classes and provides an API to easily
 * compile Katar source code onto PHP.
 */
class Katar
{
    public static function autoload($class) {
        if(strpos($class, 'Katar\\') !== 0) {
            return;
        }

        $name = str_replace('Katar\\', '', $class);

        if($name == 'KatarTokenizer') {
            require_once(__DIR__ . '/KatarTokenizer.php');
        } else if($name == 'KatarParser') {
            require_once(__DIR__ . '/KatarParser.php');
        } else {
            list($folder, $class) = explode('\\', $name);
            $file = __DIR__ . '/' . strtolower($folder) . '/' . $class . '.php';
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

    /**
     * Sets the tokenizer for the parser, if you want to use a custom tokenizer
     *
     * @param Tokenizer $tokenizer
     */
    public function setTokenizer($tokenizer) {
        $this->parser->setTokenizer($tokenizer);
    }

    /**
     * Compiles a Katar file to a PHP file
     *
     * @param string $file The path of the file to be compiled
     * @param boolean $include_file Whether the compiled file will be included
     *  after compilation
     *
     * @return If $include_file is false, returns the compiled source code, if
     *  not, returns null and includes the compiled file.
     */
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
            return null;
        } else {
            return $compiled;
        }
    }
}

// register Katar's autoloader
spl_autoload_register(__NAMESPACE__ .'\Katar::autoload');

