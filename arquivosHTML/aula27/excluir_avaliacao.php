<?php
session_start();
require "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_SESSION["usuario_id"])) {
    header("Location: login.html");
    exit;
}

$filmeId = (int)($_POST["filme_id"] ?? 0);

if ($filmeId <= 0) { exit("Filme inválido."); }

$stmt = $pdo->prepare("DELETE FROM avaliacoes WHERE usuario_id = ? AND filme_id = ?");
$stmt->execute([$_SESSION["usuario_id"], $filmeId]);

header("Location: filme.php?id=" . $filmeId);