<?php
require "conexao.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("Método inválido.");
}

$id    = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$nome  = trim($_POST["nome"] ?? "");
$idade = trim($_POST["idade"] ?? "");
$cidade = trim($_POST["cidade"] ?? "");
$email = trim($_POST["email"] ?? "");
$curso = trim($_POST["curso"] ?? "");
$periodo = trim($_POST["periodo"] ?? "");

if (!$id || $nome === "" || $idade  === "" || $cidade  === "" || $email === "" || $curso === "" || $periodo === "") {
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

$sql = "UPDATE alunos
        SET nome = :nome, idade = :idade, cidade = :cidade, email = :email, curso = :curso, periodo = :periodo
        WHERE id = :id";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "id" => $id,
        "nome" => $nome,
        "idade" => $idade,
        "cidade" => $cidade,
        "email" => $email,
        "curso" => $curso,
        "periodo" => $periodo
    ]);

    if ($stmt->rowCount() > 0) {
        echo "Aluno atualizado com sucesso!";
    } else {
        echo "Nenhuma alteração feita (ou ID não encontrado).";
    }

} catch (PDOException $e) {
    if ($e->getCode() === "23000") {
        echo "Este e-mail já está cadastrado em outro aluno.";
    } else {
        echo "Erro ao atualizar o aluno.";
    }
}

echo '<p><a href="consultar.php">Voltar a lista</a></p>';