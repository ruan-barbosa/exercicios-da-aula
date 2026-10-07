<?php
session_start();
require "conexao.php";

if (!isset($_SESSION["usuario_id"])) { header("Location: login.html"); exit; }

$id = (int)($_GET["id"] ?? $_POST["id"] ?? 0);

$stmt = $pdo->prepare("SELECT id, titulo, duracao, sinopse FROM filmes WHERE id = ?");
$stmt->execute([$id]);
$filme = $stmt->fetch();

if (!$filme) { http_response_code(404); exit("Filme não encontrado."); }

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo  = trim($_POST["titulo"] ?? "");
    $duracao = (int)($_POST["duracao"] ?? 0);
    $sinopse = trim($_POST["sinopse"] ?? "");

    if ($titulo === "" || mb_strlen($titulo) > 150 || $duracao <= 0 || $sinopse === "") {
        $erro = "Preencha todos os campos corretamente.";
        // mantém o que o usuário digitou no formulário
        $filme["titulo"]  = $titulo;
        $filme["duracao"] = $duracao;
        $filme["sinopse"] = $sinopse;
    } else {
        $stmt = $pdo->prepare("UPDATE filmes SET titulo = ?, duracao = ?, sinopse = ? WHERE id = ?");
        $stmt->execute([$titulo, $duracao, $sinopse, $id]);
        header("Location: filme.php?id=" . $id);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar filme</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="container-central">
        <section class="card-principal">
            <form method="POST" class="form-login">
                <h1>Editar filme</h1>

                <?php if ($erro): ?><p><?= htmlspecialchars($erro) ?></p><?php endif; ?>

                <input type="hidden" name="id" value="<?= (int)$filme["id"] ?>">

                <div class="campo">
                    <label for="titulo">Título</label>
                    <input type="text" id="titulo" name="titulo" maxlength="150" required
                           value="<?= htmlspecialchars($filme["titulo"]) ?>">
                </div>
                <div class="campo">
                    <label for="duracao">Duração (minutos)</label>
                    <input type="number" id="duracao" name="duracao" min="1" required
                           value="<?= (int)$filme["duracao"] ?>">
                </div>
                <div class="campo">
                    <label for="sinopse">Sinopse</label>
                    <textarea id="sinopse" name="sinopse" rows="4" required><?= htmlspecialchars($filme["sinopse"]) ?></textarea>
                </div>

                <button type="submit">Salvar alterações</button>
            </form>
        </section>
        <div class="mudar-pagina"><a href="filme.php?id=<?= (int)$filme["id"] ?>">Cancelar</a></div>
    </main>
</body>
</html>