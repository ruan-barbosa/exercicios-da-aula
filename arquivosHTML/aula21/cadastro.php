<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $usuario = trim($_POST['usuario'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (empty($usuario) || empty($senha)) {
        echo "Preencha todos os campos.";
        exit;
    }

    $usuario = htmlspecialchars($usuario);

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=aula21;charset=utf8mb4", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Criptografa a senha antes de salvar
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Prepared statement evita SQL Injection
        $stmt = $pdo->prepare("INSERT INTO usuarios (usuario, senha) VALUES (?, ?)");
        $stmt->execute([$usuario, $senha_hash]);

        echo "Usuário cadastrado com sucesso!";
        echo '<br><a href="index.html">Ir para o login</a>';

    } catch (PDOException $e) {
        // Erro 1062 = usuário duplicado (por causa do UNIQUE na coluna)
        if ($e->getCode() == 23000) {
            echo "Esse usuário já existe. Escolha outro nome.";
        } else {
            echo "Erro ao cadastrar: " . $e->getMessage();
        }
    }
}
?>