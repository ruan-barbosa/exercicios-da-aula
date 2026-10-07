<?php
session_start();
require "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_SESSION["usuario_id"])) {
    header("Location: login.html");
    exit;
}

$filmeId    = (int)($_POST["filme_id"] ?? 0);
$nota       = (float)str_replace(",", ".", $_POST["nota"] ?? "");
$comentario = trim($_POST["comentario"] ?? "");

if ($filmeId <= 0 || $nota < 0 || $nota > 5 || mb_strlen($comentario) > 500) {
    exit("Dados inválidos.");
}

$comentario = $comentario === "" ? null : $comentario;

$stmt = $pdo->prepare(
    "INSERT INTO avaliacoes (usuario_id, filme_id, nota, comentario)
     VALUES (?,?,?,?)
     ON DUPLICATE KEY UPDATE nota = ?, comentario = ?"
);
$stmt->execute([
    $_SESSION["usuario_id"], $filmeId, $nota, $comentario,
    $nota, $comentario,
]);

header("Location: filme.php?id=" . $filmeId);