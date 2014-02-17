# Installation
The recommended instalation method is using Composer. To do that simply add it 
as a dependency in your ```composer.json``` file.

    {
        "require": {
            "gosukiwi/katar": "dev-master"
        }
    }

Now by running ```php composer.phar install``` composer will download Katar
for you. Until version 1.0 it's recommended to use "dev-master" as version.

## Installing Without Composer
Optinally you can install Katar without composer, just clone the git repo
and include ```Katar.php```, that class will autoload everything else needed.

# Usage
```Katar``` is the main class for using the library, when instantiating it you 
need to provide a folder path, Katar will save all cached files there, once 
Katar is instantiated, all you need to do is call the ```compile``` method and 
provide a file path to compile the desired file.

    // the cache directory is "cache/"
    $katar = new \Katar\Katar('cache/');

    // compile myFile and include the compiled PHP file
    $katar->render('myFile.katar.php');
    
    // compile with added context, so the compiled file can use
    // $name and $age variables
    $katar->render('myFile.katar.php', array(
        'name' => 'Mike',
        'age' => 22
    ));

    // compile and get compiled PHP code as a string
    $code = $katar->compile('myFile.katar.php');

If you just want to compile a string containing Katar source code, you can do
so by calling the ```compileString``` method.
    
    // compiles the string, and returns another string containing the
    // compiled PHP code
    $code = $katar->compileString($myKatarString);

