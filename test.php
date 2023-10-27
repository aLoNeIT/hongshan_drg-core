<?php

$data = [
    'A' => 1,
    'P' => 2,
    'Y' => 3,
    'Z' => 4,
    'B' => 2,
    'M' => 11,
    'C' => 12,
];
var_dump($data);
foreach ($data as $key => $value) {
    echo "{$key}-{$value}", PHP_EOL;
}
