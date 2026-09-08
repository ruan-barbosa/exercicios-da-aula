<?php

$a = 1;
$b = -5;
$c = 6;

if ($a == 0) {
    echo "Impossivel calcular";
    exit;
}

$delta = $b ** 2 - 4*$a*$c;

if ($delta < 0) {
    echo "Impossivel calcular";
} elseif ($delta == 0) {
    $x = -$b / (2 * $a);
    echo "R = $x";
} else {
    $x1 = (-$b + sqrt($delta)) / (2 * $a);
    $x2 = (-$b - sqrt($delta)) / (2 * $a);
    echo "R1 = $x1" . "<br>" . "R2 = $x2";
}