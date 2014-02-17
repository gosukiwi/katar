# Custom Filters
To create a custom filter, just create a class to hold your filters.

    class MyFilter
    {
        public function doSomething($str) {
            return trim($str);
        }
    }

And register them to Katar

    $filter = new MyFilter;
    $katar->registerFilter('do_something', array($filter, 'doSomething'));

The first argument is the name, it doesn't really have to match the method name
of your class, then, an array containing an instance of your class and the
method name to use when the filter is called.

Once you register your filter, you can call it by doing

    <p>{{ $my_value | do_something }}</p>


