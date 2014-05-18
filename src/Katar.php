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
    public static $VERSION = '0.3.0';

    public static function autoload($class) {
        if(strpos($class, 'Katar\\') !== 0) {
            return;
        }

        $ds = DIRECTORY_SEPARATOR; 
        $name = str_replace(array('Katar\\', '\\'), array(__DIR__ . $ds, $ds), $class) . '.php';
        if(file_exists($name)) {
            require_once($name);
        }
    }

    private $views_cache;
    private $parser;

    public function __construct($views_cache) {
        $this->views_cache = $views_cache;

        $tokenizer = new Tokenizer();
        $this->parser = new Parser();
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
     * Registers a filter function to Katar
     *
     * Example: $katar->registerFilter(array($obj, 'myFilter'));
     *
     * @param string $name The name of the filter, as it will be called from 
     *  whithin the template
     *
     * @param mixed $filter The filter to be registered, it can be either a string
     * or an array, containing an instance of an object in the first element, and a string
     * with the method to be called in the second element.
     */
    public function registerFilter($name, $filter) {
        $this->parser->registerFilter($name, $filter);
    }

    /**
     * Compiles a Katar file to a PHP file, and includes it
     *
     * @param string $file The path of the file to be compiled
     *
     * @param array $env The environmental variables to be added to the included 
     * file's context
     */
    public function render($file, $env = array()) {
        if(!file_exists($file)) {
            throw new \Exception("Could not compile $file, file not found");
        }

        $source = file_get_contents($file);
        $source_update = filemtime($file);

        $cache_file = $this->views_cache . '/' . md5($file);

        if(!file_exists($cache_file) || filemtime($cache_file) < filemtime($file)) {
            $compiled = $this->compileString($source);
            file_put_contents($cache_file, $compiled);
        }

        extract($env);
        require($cache_file);
    }

    /**
     * Compiles a Katar file to a PHP file, returning the compiled code
     *
     * @param string $file The path of the file to be compiled
     *
     * @return string The compiled PHP code
     */
    public function compile($file) {
        if(!file_exists($file)) {
            throw new \Exception("Could not compile $file, file not found");
        }

        $source = file_get_contents($file);
        $source_update = filemtime($file);

        $cache_file = $this->views_cache . '/' . md5($file);
        $compiled = null;

        if(!file_exists($cache_file) || filemtime($cache_file) < filemtime($file)) {
            $compiled = $this->compileString($source);
            file_put_contents($cache_file, $compiled);
        } else {
            $compiled = file_get_contents($cache_file);
        }

        return $compiled;
    }

    /**
     * Compiles a string containing Katar source code onto PHP
     *
     * @param string $str The string to be compiled
     *
     * @return Compiled PHP source code
     */
    public function compileString($str) {
        return $this->parser->compile($str);
    }
}

// register Katar's autoloader
spl_autoload_register(__NAMESPACE__ .'\Katar::autoload');

