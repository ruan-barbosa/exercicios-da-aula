<?php
session_start();
require "conexao.php";

if (!isset($_SESSION["usuario_id"])) { header("Location: login.html"); exit; }

$id = (int)($_GET["id"] ?? 0);

$stmt = $pdo->prepare(
    "SELECT f.*, AVG(a.nota) AS media, COUNT(a.id) AS total
     FROM filmes f
     LEFT JOIN avaliacoes a ON a.filme_id = f.id
     WHERE f.id = ?
     GROUP BY f.id"
);
$stmt->execute([$id]);
$filme = $stmt->fetch();

if (!$filme) { http_response_code(404); exit("Filme não encontrado."); }

$stmt = $pdo->prepare("SELECT nota, comentario FROM avaliacoes WHERE usuario_id = ? AND filme_id = ?");
$stmt->execute([$_SESSION["usuario_id"], $id]);
$minha = $stmt->fetch();

$stmt = $pdo->prepare(
    "SELECT u.nome, a.nota, a.comentario, a.criado_em
     FROM avaliacoes a
     JOIN usuario u ON u.id = a.usuario_id
     WHERE a.filme_id = ?
     ORDER BY a.criado_em DESC"
);
$stmt->execute([$id]);
$avaliacoes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($filme["titulo"]) ?> - Ruview</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <a href="index.php">← Voltar</a>

        <h1><?= htmlspecialchars($filme["titulo"]) ?></h1>
        <p><?= (int)$filme["duracao"] ?> min</p>
        <p><?= htmlspecialchars($filme["sinopse"]) ?></p>
        <p>
            <?php if ($filme["total"] > 0): ?>
                Nota média: <?= number_format($filme["media"], 1, ",", ".") ?>/5
                (<?= $filme["total"] ?> avaliação(ões))
            <?php else: ?>
                Ainda sem avaliações
            <?php endif; ?>
        </p>
        
        <div class="acoes">
            <a href="editar_filme.php?id=<?= (int)$filme["id"] ?>" class="botao">Editar filme</a>

            <form action="excluir_filme.php" method="POST"
                onsubmit="return confirm('Excluir este filme e todas as avaliações dele?');">
                <input type="hidden" name="id" value="<?= (int)$filme["id"] ?>">
                <button type="submit" class="botao perigo">Excluir filme</button>
            </form>
        </div>

        <h2><?= $minha ? "Editar minha avaliação" : "Avaliar este filme" ?></h2>
        <form action="avaliar.php" method="POST">
            <input type="hidden" name="filme_id" value="<?= $id ?>">

            <label for="nota">Nota (0 a 5)</label>
            <input type="number" id="nota" name="nota" min="0" max="5" step="0.5"
                   value="<?= $minha ? htmlspecialchars($minha["nota"]) : "" ?>" required>

            <label for="comentario">Comentário</label>
            <textarea id="comentario" name="comentario" maxlength="500" rows="3"><?= $minha ? htmlspecialchars($minha["comentario"] ?? "") : "" ?></textarea>

            <button type="submit">Salvar avaliação</button>
        </form>

        <h2>Avaliações</h2>
        <?php foreach ($avaliacoes as $a): ?>
            <article class="avaliacao">
                <strong><?= htmlspecialchars($a["nome"]) ?></strong>
                (<?= number_format($a["nota"], 1, ",", ".") ?>/5)
                <p><?= htmlspecialchars($a["comentario"] ?? "") ?></p>
            </article>
        <?php endforeach; ?>
    </main>
</body>
</html>