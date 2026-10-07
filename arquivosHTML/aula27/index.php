<?php
session_start();
require "conexao.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.html");
    exit;
}

$sql = "SELECT f.id, f.titulo, f.duracao, f.sinopse,
               AVG(a.nota) AS media,
               COUNT(a.id) AS total
        FROM filmes f
        LEFT JOIN avaliacoes a ON a.filme_id = f.id
        GROUP BY f.id
        ORDER BY f.titulo";
$filmes = $pdo->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruview</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <span>Olá, <?= htmlspecialchars($_SESSION["usuario_nome"]) ?></span>
        <a href="logout.php">Sair</a>
    </header>

    <main>
        <h1>Filmes</h1>
        <a href="adicionar_filme.php" class="botao">+ Adicionar</a>

        <?php if (!$filmes): ?>
            <p>Nenhum filme cadastrado ainda.</p>
        <?php endif; ?>

        <?php foreach ($filmes as $f): ?>
            <article class="filme">
                <h2><?= htmlspecialchars($f["titulo"]) ?></h2>
                <p><?= (int)$f["duracao"] ?> min</p>
                <p><?= htmlspecialchars($f["sinopse"]) ?></p>
                <p>
                    <?php if ($f["total"] > 0): ?>
                        Nota: <?= number_format($f["media"], 1, ",", ".") ?>/5
                        (<?= $f["total"] ?> avaliação(ões))
                    <?php else: ?>
                        Ainda sem avaliações
                    <?php endif; ?>
                </p>
                <a href="filme.php?id=<?= (int)$f["id"] ?>">Ver / avaliar</a>
            </article>
        <?php endforeach; ?>
    </main>
</body>
</html>