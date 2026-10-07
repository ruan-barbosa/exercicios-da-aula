<?php
session_start();
require "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_SESSION["usuario_id"])) {
    header("Location: login.html");
    exit;
}

$id = (int)($_POST["id"] ?? 0);

if ($id <= 0) { exit("Filme inválido."); }

$stmt = $pdo->prepare("DELETE FROM filmes WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");