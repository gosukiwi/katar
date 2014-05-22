<?php
require_once __DIR__ . '/../src/Katar.php';

$katar = new Katar\Katar(__DIR__);

$example_1 = $katar->render('example-1.katar.html', array(
    'people' => array (
        array('name' => 'Mike O\'Malley'),
    ),
));

echo $example_1;
