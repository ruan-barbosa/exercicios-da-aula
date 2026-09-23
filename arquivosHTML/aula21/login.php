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

        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->execute([$usuario]);
        $usuario_encontrado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario_encontrado && password_verify($senha, $usuario_encontrado['senha'])) {
            echo "<h2>Login bem-sucedido!</h2>";
            echo "Bem-vindo, " . $usuario_encontrado['usuario'] . "!";
        } else {
            echo "<h2>Usuário ou senha incorretos.</h2>";
            echo '<a href="index.html">Tentar novamente</a>';
        }
        echo '<br><a href="index.html">Início</a>';

    } catch (PDOException $e) {
        echo "Erro ao conectar: " . $e->getMessage();
    }
}
?>