<?php
require_once __DIR__ . '/../src/Katar.php';

$katar = new Katar\Katar(
    __DIR__ . '/views', __DIR__ . '/cache', true);

$example_1 = $katar->render('base.katar.html', array(
    'people' => array (
        array('name' => 'Mike O\'Malley'),
        array('name' => 'Alice'),
        array('name' => 'Bob'),
    ),
    'view' => 'example-1.katar.html',
));

echo $example_1;
