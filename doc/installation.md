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

    // pass the directory where all our templates will be stored
    // optionally you can specify the cache directory as second
    // parameter
    $katar = new \Katar\Katar(__DIR__ . '/templates');

    // compile myFile and store it in the $html variable
    $html = $katar->render('myFile.katar.php');
    
    // compile with added context, so the compiled file can use
    // $name and $age variables
    $html = $katar->render('myFile.katar.php', array(
        'name' => 'Mike',
        'age' => 22
    ));

    // write our output
    echo $html;


[Go back](index.md)
