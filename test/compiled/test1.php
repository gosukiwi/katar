<?php
function katar_50ef8c5b55bc21e8690c8eb917e2ebb7($args) {
extract($args);
$output = null;
$output .= '&lt;h1&gt;Test Katar&lt;/h1&gt;

';
if($age > 22) {
$output .= '    &lt;p&gt;The age is bigger than 22&lt;/p&gt;
';
} else {
$output .= '    &lt;p&gt;The age is not bigger than 22&lt;/p&gt;
';
}
$output .= '
&lt;h2&gt;For demonstration&lt;/h2&gt;

';
$for_index = 0; foreach($people as $person) {
$output .= '    &lt;p&gt;';
$output .= $person['name'];
$output .= '&lt;/p&gt;
';
$for_index++; }
$output .= '
&lt;p&gt;My name is ';
$output .= $name;
$output .= '&lt;/p&gt;
';

return $output;
}
