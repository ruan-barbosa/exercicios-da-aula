<?php
require "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: cadastro.html");
    exit;
}

$nome = trim($_POST["nome"] ?? "");
$email = trim($_POST["email"] ?? "");
$senha = $_POST["senha"] ?? "";

if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 6) {
    exit('Dados inválidos.');
}

$stmt = $pdo->prepare("SELECT id FROM usuario WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    exit("E-mail já cadastrado.");
}

$hash = password_hash($senha, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO usuario (nome, email, senha) VALUES (?,?,?)");
$stmt->execute([$nome, $email, $hash]);

header("Location: login.html");
