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
    <}

# Documentation
Documentaiton can be found at ```docs/```, or you can [read it online on GitHub]
(https://github.com/gosukiwi/katar/tree/master/doc/index.md).

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

