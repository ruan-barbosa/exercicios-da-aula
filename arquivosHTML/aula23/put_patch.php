<?php
$metodo = $_SERVER["REQUEST_METHOD"];

if ($metodo === "PUT" || $metodo === "PATCH") {
    $corpo = file_get_contents("php://input");
    echo"Atualização recebida";
}

if ($metodo === "DELETE") {
    echo "Pedido de remoção recebido";
}