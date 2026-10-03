<?php
require "conexao.php";
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Método invalido";
    exit;
}

$id = (int) ($_POST["id"] ?? 0);
$nome = trim($_POST["nome"] ?? "");
$email = trim($_POST["email"] ?? "");
$curso = trim($_POST["curso"] ?? "");

if ($id <= 0 || $nome === "" || $email === "" || $curso ==="") {
    echo "Preencha todos os campos.";
    exit;
}

$sql = "UPDATE alunos
        SET nome = :nome, email = :email, curso = :curso
        WHERE id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    "id" => $id, "nome" => $nome,
    "email" => $email, "curso => $curso"
]);

echo "Aluno atualizado com sucesso!";