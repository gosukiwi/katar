<?php
require_once __DIR__ . '/../src/Katar.php';


$katar = new Katar\Katar(
    __DIR__ . '/views', __DIR__ . '/cache');

// Register custom filter
$filter = new DogeFilter;
$katar->registerFilter('doge', array($filter, 'Doge'));

// Render
$html = $katar->render('base.katar.html', array(
    'people' => array (
        array('name' => 'Thomas O\'Malley'),
        array('name' => 'Duchess'),
        array('name' => 'Marie'),
        array('name' => 'Berlioz'),
        array('name' => 'Toulouse'),
    ),
    'view' => 'body.katar.html',
));

echo $html;


class DogeFilter
{
    public function Doge($str) {
        return "so $str, much wow";
    }
}
