<?php

$produto = "Teclado";
$preco = 120.00;
$quantidade = 2;
$desconto = 10;
const NOME_LOJA = "Tech Store";

function calcularDesconto($preco, $quantidade, $desconto) {
    return ($preco - $desconto) * $quantidade;
}

echo "Loja: " . NOME_LOJA;
echo "<br>";
echo "Produto: " . $produto;
echo "<br>";
echo "Quantidade: " . $quantidade;
echo "<br>";
echo "Valor Bruto: R$ " . $preco * $quantidade;
echo "<br>";
echo "Desconto: R$ " . $desconto;
echo "<br>";
echo "R$ " . calcularDesconto($preco, $quantidade, $desconto);