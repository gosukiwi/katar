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

    <p>My name is {{ $name }}</p>

    <?php echo 'Now I don\'t want to use Katar, I just use PHP'; ?>

# Usage
Katar is the main class for using the library, when instantiating it you need
to provide a folder path, Katar will save all cached files there, once Katar is
instantiated, all you need to do is call the ```compile``` method and provide 
a file path to compile the desired file.

    $katar = new \Katar\Katar('cache/');
    // compile myFile and include the compiled PHP file
    $katar->compile('myFile.katar.php');
    // compile and get compiled code
    $code = $katar->compile('myFile.katar.php', false);

If you just want to compile a string containing Katar source code, you can do
so by calling the ```compileString``` method.
    
    $code = $katar->compileString($myKatarString);

# Using Katar with Composer
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

# TODO
There's still some polishing to do, as soon as It's finished I'll start the
versioning :)
