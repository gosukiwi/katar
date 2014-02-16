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
    public static $VERSION = '0.0.2';

    public static function autoload($class) {
        if(strpos($class, 'Katar\\') !== 0) {
            return;
        }

        $name = str_replace('Katar\\', '', $class);

        if($name == 'Tokenizer') {
            require_once(__DIR__ . '/Tokenizer.php');
        } else if($name == 'Parser') {
            require_once(__DIR__ . '/Parser.php');
        } else if(false !== strpos($name, '\\')) {
            list($folder, $class) = explode('\\', $name);
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
     * Compiles a Katar file to a PHP file
     *
     * @param string $file The path of the file to be compiled
     * @param boolean $include_file Whether the compiled file will be included
     *  after compilation
     *
     * @return If $include_file is false, returns the compiled source code, if
     *  not, returns null and includes the compiled file.
     */
    public function compile($file, $env = array(), $include_file = true) {
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
        }

        if($include_file) {
            extract($env);
            require($cache_file);
            return null;
        } else {
            return $compiled;
        }
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

