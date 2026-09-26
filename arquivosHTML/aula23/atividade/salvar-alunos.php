<?php

$host = "localhost";
$porta = "3306";
$banco = "escola_formulario";
$usuario = "root";
$senha = "";

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$porta;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        echo "Método inválido.";
        exit;
    }

    $cpf = trim($_POST["cpf"] ?? "");
    $nome = trim($_POST["nome"] ?? "");
    $idade = trim($_POST["idade"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $telefone = trim($_POST["telefone"] ?? "");
    $endereco = trim($_POST["endereco"] ?? "");
    $curso = trim($_POST["curso"] ?? "");
    $turma = trim($_POST["turma"] ?? "");
    
    if ($cpf === "" || $nome === "" || $idade === "" || $email === "" || $telefone === "" || $endereco === "" || $curso === "" || $turma === "") {
        echo "Preencha todos os campos";
        exit;
    }
    
    $sql = "insert into alunos (cpf, nome, idade, email, telefone, endereco, curso, turma)
        values (:cpf, :nome, :idade, :email, :telefone, :endereco, :curso, :turma)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        "cpf" => $cpf,
        "nome" => $nome,
        "idade" => $idade,
        "email" => $email,
        "telefone" => $telefone,
        "endereco" => $endereco,
        "curso" => $curso,
        "turma" => $turma
    ]);
    
    echo "Aluno cadastrado com sucesso!";
    echo '<br><a href="index.html">Ir para a tela inicial</a>';
    
} catch (PDOException $erro) {
    echo "Erro: " . $erro->getMessage();
}