<?php
require "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Método inválido.");
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id) {
    exit("Informe um ID válido.");
}

$sql = "DELETE FROM alunos WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([":id" => $id]);

if ($stmt->rowCount() > 0) {
    echo "Aluno removido com sucesso!";
} else {
    echo "Aluno não encontrado.";
}