<?php
require "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Método inválido.");
}

$nome  = trim($_POST["nome"] ?? "");
$idade = trim($_POST["idade"] ?? "");
$cidade = trim($_POST["cidade"] ?? "");
$email = trim($_POST["email"] ?? "");
$curso = trim($_POST["curso"] ?? "");
$periodo = trim($_POST["periodo"] ?? "");

if ($nome === "" || $idade  === "" || $cidade  === "" || $email === "" || $curso === "" || $periodo === "") {
    exit("Preencha todos os campos.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("E-mail inválido.");
}

if (filter_var($idade, FILTER_VALIDATE_INT) === false || (int) $idade < 1) {
    exit("Idade inválida.");
}

$periodosPermitidos = ["Manhã", "Tarde", "Noite"];
if (!in_array($periodo, $periodosPermitidos, true)) {
    exit("Período inválido.");
}

$sql = "INSERT INTO alunos (nome, idade, cidade, email, curso, periodo)
        VALUES (:nome, :idade, :cidade, :email, :curso, :periodo)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "nome" => $nome,
        "idade" => $idade,
        "cidade" => $cidade,
        "email" => $email,
        "curso" => $curso,
        "periodo" => $periodo
    ]);

    echo "Aluno cadastrado com sucesso!";
} catch (PDOException $e) {
    if ($e->getCode() === "23000") {
        echo "Este e-mail já está cadastrado.";
    } else {
        echo "Erro ao cadastrar o aluno.";
    }
}

echo '<p><a href="index.html">Voltar ao início</a></p>';