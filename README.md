# Katar
Katar is a simple PHP templating engine based on Blade. 

    @if $age > 22
        <p>The age is bigger than 22</p>
    @else
        <p>The age is not bigger than 22</p>
    @end

Each katar directive must be in it's own line, exept for the value directive

## Syntax

    @if $age > 22
        <p>The age is bigger than 22</p>
    @else
        <p>The age is not bigger than 22</p>
    @end

    @for $person in $people
        <p>{{ $person->name }}</p>
    @endfor

    <p>My name is {{ $name }}</p>

# TODO
There's still some polishing to do, as soon as It's finished I'll start the
versioning :)
