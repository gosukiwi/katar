# Katar
Katar is a tiny PHP templating engine based on Blade. It was designed to
be simple and clean.

Katar is easy to include into any existing project, just require it with
composer! It even has a Silex service provider if you are interested.

For more information see the documentation.

## Syntax at a glance

    @if $age > 22
        <p>The age is bigger than 22</p>
    @else
        <p>The age is not bigger than 22</p>
    @endif

    @for $person in $people
        <p>Person #{{ $for_index + 1 }}: {{ $person->name }}</p>
    @endfor

    <p>My name is {{ $name | strtoupper }}</p>

    @import 'myFile.katar.html'

    {>
        @this is escaped code, everything inside the escape tags won't get
        processed by Katar.
    <}

# Documentation
Documentation can be found at ```docs/```, or you can [read it online on GitHub]
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

