<?php

$n = 550;
echo $n . "\n";
echo "<br>";
$notas = [100, 50, 20, 10, 5, 2];
foreach ($notas as $nota) {
    $qtd = intdiv($n, $nota);

    echo $qtd . " nota(s) de R$ " . $nota . ",00\n";
    echo "<br>";
    $n %= $nota;
}