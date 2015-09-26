<?php

$array1 = include __DIR__.'/../directory1/expected-global-local.php';
$array2 = include __DIR__.'/expected-local-global.php';

return array_merge_recursive($array1, $array2);
