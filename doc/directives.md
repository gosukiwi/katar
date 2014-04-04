# Directives
Directives are translated to PHP code, everything else is ignored. The 
following directives are available in Katar

## Value
The value directive just evaluates and renders the value defined inside

```php
<p>Hello! Your name is {{ $name }}</p>
````

## If
Syntactic sugar for PHP's IF. The following example demonstrates how to use
this control structure.

```php
@if $name == 'Mike'
    <p>Hi, Mike!</p>
@else if $name == 'Alice'
    <p>Sup, Alice!</p>
@else
    <p>Hello {{ $name }}</p>
@endif
```

## For
Syntactic sugar for PHP's FOREACH.

```php
@for $person in $people
    {> You also have access to a variable called $for_index, which holds the current index of the loop <}
    <p>Number: {{ $for_index }}</p>
    <p>Name: {{ $person->name }}</p>
    <p>Age: {{ $person->age }}</p>
@endfor
```

# Filters
When displaying values, you can optionally filter them, a filter is basically a
function which takes a string as argument, and returns a transformed string, you
can use any of PHP functions such as strtolower, trim, etc.

```php
<p>Hello {{ $name | strtoupper }}</p>
```

You can also chain filters

```php
<p>Hello {{ $name | strtoupper | trim }}</p>
```

It's important to note that functions must only take one argument and return
a string, you can [create your own filters](custom-filters.md) if you need more
filters.

