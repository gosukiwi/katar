# Katar
Katar is a simple PHP templating engine based on Blade. It was designed to
be simple, and have a clean syntax. Katar doesn't get in your way, you can mix
PHP and Katar! 

## Syntax

    @if $age > 22
        <p>The age is bigger than 22</p>
    @else
        <p>The age is not bigger than 22</p>
    @endif

    @for $person in $people
        <p>{{ $person->name }}</p>
    @endfor

    <p>My name is {{ $name | strtoupper }}</p>

    <?php echo 'Now I don\'t want to use Katar, I just use PHP'; ?>

    {>
        @this is escaped code, everything inside the escape tags won't get
        processed by Katar.
    }>

# Usage
Katar is the main class for using the library, when instantiating it you need
to provide a folder path, Katar will save all cached files there, once Katar is
instantiated, all you need to do is call the ```compile``` method and provide 
a file path to compile the desired file.

    // the cache directory is "cache/"
    $katar = new \Katar\Katar('cache/');
    // compile myFile and include the compiled PHP file
    $katar->compile('myFile.katar.php');
    
    // compile with added context, so the compiled file can use
    // $name and $age variables
    $katar->compile('myFile.katar.php', array(
        'name' => 'Mike',
        'age' => 22
    ));

    // compile and get compiled code
    $code = $katar->compile('myFile.katar.php', array(), false);

If you just want to compile a string containing Katar source code, you can do
so by calling the ```compileString``` method.
    
    $code = $katar->compileString($myKatarString);

## Using Katar with Composer
There are several ways to include Katar in your project, with Composer's beeing
the prefered one. To do that simply add it as a dependency in your 
```composer.json``` file.

    {
        "require": {
            "gosukiwi/katar": "dev-master"
        }
    }

Now by running ```php composer.phar install``` composer will download Katar
for you.

# Custom Filters
You can add custom filters to Katar, just create a base class to hold your 
filter

    class MyFilter
    {
        public function doSomething($str) {
            return trim($str);
        }
    }

And register it to Katar

    $filter = new MyFilter;
    $katar->registerFilter('do_something', array($filter, 'doSomething'));

The first argument is the name, it doesn't really have to match the method name
of your class, then, an array containing an instance of your class and the
method name to use when the filter is called.

Once you register your filter, you can call it by doing

    <p>{{ $my_value | do_something }}</p>

# Contributing
If you like Katar and would like to contribute just pick an issue, send me
a pull request and if everything seems right it will get merged :)

# Versioning
Katar uses [Semantic Versioning](http://semver.org/), quoting from their website:

> Given a version number MAJOR.MINOR.PATCH, increment the:
>  
> MAJOR version when you make incompatible API changes,
> MINOR version when you add functionality in a backwards-compatible manner, and
> PATCH version when you make backwards-compatible bug fixes.


