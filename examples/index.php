<?php
require_once __DIR__ . '/../src/Katar.php';

$katar = new Katar\Katar(
    __DIR__ . '/views', __DIR__ . '/cache', true);

$example_1 = $katar->render('example-1.katar.html', array(
    'people' => array (
        array('name' => 'Mike O\'Malley'),
    ),
    'partial' => 'example-1',
));

echo $example_1;
