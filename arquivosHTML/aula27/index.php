<?php
session_start();
require "conexao.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.html");
    exit;
}

require "sincronizar.php";

$erroTmdb = "";
$importados = (int) $pdo->query("SELECT COUNT(*) FROM filmes WHERE tmdb_id IS NOT NULL")->fetchColumn();

if ($importados === 0) {
    try {
        if (sincronizar_filmes($pdo, 3) === 0) {
            $erroTmdb = "Não foi possível importar filmes. Confira o token em config.php.";
        }
    } catch (Throwable $e) {
        $erroTmdb = "Erro ao importar filmes: " . $e->getMessage();
    }
}

$busca = trim($_GET["q"] ?? "");

$sql = "SELECT f.id, f.titulo, f.duracao, f.sinopse, f.poster,
               AVG(a.nota) AS media,
               COUNT(a.id) AS total
        FROM filmes f
        LEFT JOIN avaliacoes a ON a.filme_id = f.id
        WHERE f.titulo LIKE ?
        GROUP BY f.id
        ORDER BY f.titulo";
$stmt = $pdo->prepare($sql);
$stmt->execute(["%" . addcslashes($busca, "%_\\") . "%"]);
$filmes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruview</title>
    <link rel="icon" type="image/png" href="img/favicon.png">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <a href="index.php"><img src="img/logo.png" alt="Ruview" class="logo-header"></a>
        <span>
            Olá, <?= htmlspecialchars($_SESSION["usuario_nome"]) ?>
            <a href="logout.php">Sair</a>
        </span>
    </header>

    <main>
        <h3>Filmes</h3>

        <form method="get" class="busca">
            <input type="search" name="q" value="<?= htmlspecialchars($busca) ?>" placeholder="Buscar filme pelo título"
                aria-label="Buscar filme">
            <button type="submit">Buscar</button>
            <?php if ($busca !== ""): ?>
                <a href="index.php" class="limpar">Limpar</a>
            <?php endif; ?>
        </form>

        <?php if ($erroTmdb): ?>
            <p><?= htmlspecialchars($erroTmdb) ?></p>
        <?php endif; ?>

        <?php if (!$filmes): ?>
            <p>
                <?= $busca !== ""
                    ? "Nenhum filme encontrado para \"" . htmlspecialchars($busca) . "\"."
                    : "Nenhum filme cadastrado ainda." ?>
            </p>
        <?php endif; ?>

        <?php foreach ($filmes as $f): ?>
            <article class="filme">
                <?php if ($f["poster"]): ?>
                    <img class="poster" src="https://image.tmdb.org/t/p/w185<?= htmlspecialchars($f["poster"]) ?>"
                        alt="Pôster de <?= htmlspecialchars($f["titulo"]) ?>">
                <?php endif; ?>

                <div class="filme-info">
                    <h2><?= htmlspecialchars($f["titulo"]) ?></h2>
                    <p><?= (int) $f["duracao"] ?> min</p>
                    <p><?= htmlspecialchars($f["sinopse"]) ?></p>
                    <p class="nota">
                        <?php if ($f["total"] > 0): ?>
                            ★ <?= number_format($f["media"], 1, ",", ".") ?>/5
                            (<?= $f["total"] ?> avaliação(ões))
                        <?php else: ?>
                            Ainda sem avaliações
                        <?php endif; ?>
                    </p>
                    <a href="filme.php?id=<?= (int) $f["id"] ?>" class="botao-contorno">Ver / avaliar</a>
                </div>
            </article>
        <?php endforeach; ?>
    </main>
    <footer>
        <p>This product uses the TMDB API but is not endorsed or certified by TMDB.</p>
    </footer>
</body>

</html>