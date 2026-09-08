<?php

$texto = "25";
$idade = (int) $texto;
$precoTexto = "15.99";
$preco = (float) $precoTexto;
var_dump($idade);
var_dump($preco);
echo "<br>";

$nome = "Ana";
$idade = 20;
$ativo = true;
var_dump($nome);
var_dump($idade);
var_dump($ativo);
echo "<br>";

$apelido = "feio";
$nomeExibicao = $apelido ?? "Visitante";

echo $nomeExibicao;
echo "<br>";

$nome = " Maria ";
$nome = trim($nome);
echo strtoupper($nome);
echo "<br>";
echo strlen($nome);
echo "<br>";

$taxa = 10;

function calcular($valor, $taxa) {
    $desconto = $valor * ($taxa / 100);
    return $valor - $desconto;
}
echo calcular(200, $taxa);
echo "<br>";

function calcularMedia($n1, $n2) {
    return ($n1 + $n2) / 2;
}
$m1 = calcularMedia(8, 6);
$m2 = calcularMedia(9, 7);
echo $m1;
echo "<br>";
echo $m2;

$nomes = ["Bruno", "Ana", "Carlos"];
echo count($nomes);
array_push($nomes, "Daniel");
sort($nomes);
foreach ($nomes as $nome) {
    echo "<br>" . "$nome";
}
echo "<br>";

$aluno = [
    "nome" => "Ana",
    "nota" => 8
];
if (isset($aluno["nota"])) {
    echo $aluno["nota"];
}
$frutas = ["maçã", "uva"];
    if (in_array("uva", $frutas)) {
        echo "<br>Encontrada";
    }
echo "<br>";

echo date("d/m/Y");
echo "<br>";
echo date("H:i:s");
echo "<br>";
echo date("d/m/Y H:i");
echo "<br>";

$linha = "Ana; 8.5<br>";
file_put_contents(
    "notas.txt",
    $linha,
    FILE_APPEND
);
$conteudo = file_get_contents("notas.txt");
echo $conteudo;

function calcularMedia($n1, $n2) {
    return ($n1 + $n2) / 2;
}
function situacao($media) {
    return $media >= 7
    ? "Aprovado"
    : "Reprovado";
}

$media = calcularMedia(8, 7);
echo situacao($media);