<?php
session_start();
require "conexao.php";

if (!isset($_SESSION["usuario_id"])) { header("Location: login.html"); exit; }

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo  = trim($_POST["titulo"] ?? "");
    $duracao = (int)($_POST["duracao"] ?? 0);
    $sinopse = trim($_POST["sinopse"] ?? "");

    if ($titulo === "" || mb_strlen($titulo) > 150 || $duracao <= 0 || $sinopse === "") {
        $erro = "Preencha todos os campos corretamente.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO filmes (titulo, duracao, sinopse) VALUES (?,?,?)");
        $stmt->execute([$titulo, $duracao, $sinopse]);
        header("Location: filme.php?id=" . $pdo->lastInsertId());
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar filme</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container-central">
        <section class="card-principal">
            <form method="POST" class="form-login">
                <h1>Adicionar filme</h1>

                <?php if ($erro): ?><p><?= htmlspecialchars($erro) ?></p><?php endif; ?>

                <div class="campo">
                    <label for="titulo">Título</label>
                    <input type="text" id="titulo" name="titulo" maxlength="150" required>
                </div>
                <div class="campo">
                    <label for="duracao">Duração (minutos)</label>
                    <input type="number" id="duracao" name="duracao" min="1" required>
                </div>
                <div class="campo">
                    <label for="sinopse">Sinopse</label>
                    <textarea id="sinopse" name="sinopse" rows="4" required></textarea>
                </div>

                <button type="submit">Salvar</button>
            </form>
        </section>
        <div class="mudar-pagina"><a href="index.php">Voltar</a></div>
    </main>
</body>
</html>