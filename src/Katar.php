<?php
/**
 * Katar is a simple template engine for PHP with a clean syntax which doesn't
 * get in your way.
 *
 * This class provides a singleton interface to the Katar compiler.
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
        $name = str_replace(array('Katar\\', '\\'), array(__DIR__ . $ds, $ds),
            $class) . '.php';
        if(file_exists($name)) {
            require_once($name);
        }
    }

    private $views_cache;
    private $views_path;
    private $parser;
    private $currFile;
    private $debug;
    private static $instance = null;

    public static function getInstance($views_path = null, 
        $views_cache = null, $debug = null) {
        if(is_null(self::$instance)) {
            self::$instance = new Katar($views_path, $views_cache, $debug);
        }

        return self::$instance;
    }

    public function __construct($views_path, $views_cache = null,
        $debug = false) {

        if(!is_null(self::$instance)) {
            return self::$instance;
        }

        $this->views_path = $views_path;
        $this->debug = $debug;

        if(is_null($views_cache)) {
            $views_cache = $views_path . '/cache';
        }
        $this->views_cache = $views_cache;

        $this->currFile = null;
        $tokenizer = new Tokenizer();
        $this->parser = new Parser();
        $this->parser->setTokenizer($tokenizer);

        self::$instance = $this;
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
        $file = $this->views_path . '/' . $file;

        if(!file_exists($file)) {
            throw new \Exception("Could not compile $file, file not found");
        }

        // Cache compiled HTML
        $cacheHash = md5($file . serialize($env));
        $cache_file = $this->views_cache . "/$cacheHash.cache";
        if( !$this->debug && (file_exists($cache_file) 
            && filemtime($cache_file) > filemtime($file))) {
            return file_get_contents($cache_file);
        }

        // If it's not cached, load compiled file and execute
        $this->currFile = $file;
        $hash = md5($file);
        $this->compile($file);
        $compiled_file = $this->views_cache . '/' . $hash;

        // Set a custom error handler
        set_error_handler(array($this, 'onTemplateError'));

        require_once($compiled_file);
        $output = call_user_func('katar_' . $hash, $env);

        // Restore the handler as we leave Katar
        restore_error_handler();

        file_put_contents($cache_file, $output);

        return $output;
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

        if(!file_exists($this->views_cache) && !mkdir($this->views_cache)) {
            throw new \Exception("Could no create cache directory." . 
                " Make sure you have write permissions.");
        }

        $hash = md5($file);
        $compiled_file = $this->views_cache . '/' . $hash;
        $compiled = null;

        if( $this->debug || (!file_exists($compiled_file) 
            || filemtime($compiled_file) < filemtime($file))) {
            // get the katar source code and compile it
            $source = file_get_contents($file);
            $compiled = $this->compileString($source);
            $compiled = "<?php\nfunction katar_" . $hash .
                "(\$args) {\nextract(\$args);\n\$output = null;\n" . $compiled .
                "\nreturn \$output;\n}\n";

            file_put_contents($compiled_file, $compiled);
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

    /**
     * Throw an exception if there's been an error in the template,
     * this is registered as error handler before rendering any template.
     */
    public function onTemplateError($errno, $errstr) {
        throw new KatarRuntimeException('There has been an error in your ' .
            'Katar template: ' . $this->currFile . '<br>' . $errstr);
    }
}

// register Katar's autoloader
spl_autoload_register(__NAMESPACE__ .'\Katar::autoload');

class KatarRuntimeException extends \Exception {}
