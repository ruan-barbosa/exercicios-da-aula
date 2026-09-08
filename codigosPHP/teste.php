<?php

echo"Hello World";
echo"<br>";
echo"Teste";
echo"<br>";
$nome = "Ruan";
$idade = 17;
$altura = 1.70;
$sexo = "Masculino";
$seila = "seila";
echo"Olá, $nome, $idade, $altura, $sexo, $seila";
echo"<br>";
define("PI", 3.14159);
const versao = "1.0";
echo PI;
echo"<br>";
$a = 10;
$b = 3;
echo $a + $b;
echo"<br>";
echo $a . " reais";
echo"<br>";
echo ($a === 10);
echo"<br>";

$nota = 10;
if ($nota >= 7) {
    echo "Aprovado!";
} elseif ($nota >= 5) {
    echo "Recuperação";
} else {
    echo "Reprovado!";
}
echo"<br>";

switch ($nota) {
    case 10:
        echo "Execelente!";
        break;
    case 7:
        echo "Bom!";
        break;
    default:
    echo "Outro";
}
echo"<br>";

$i = 0;
while ($i < 5) {
    echo $i;
    $i++;
}
echo"<br>";

$i = 0;
do {
    echo $i;
    $i++;
} while ($i < 5);
echo"<br>";

for ($i = 0; $i < 6; $i++) {
    echo $i;
}
echo"<br>";

$frutas = ["maçã", "banana", "laranja"];
foreach ($frutas as $fruta) {
    echo $fruta;
}
?>