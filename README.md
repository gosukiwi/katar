# Katar
Katar is a simple PHP templating engine based on Blade. It was designed to
be simple, and have a clean syntax.

Katar doesn't get in your way, you can mix PHP and Katar! 

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

There are several ways to include Katar in your project, with Composer's beeing
the prefered one.

## Using Katar with Composer

# TODO
There's still some polishing to do, as soon as It's finished I'll start the
versioning :)
