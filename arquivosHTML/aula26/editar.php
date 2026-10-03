<?php
require "conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    exit("ID inválido.");
}

$stmt = $pdo->prepare("SELECT id, nome, idade, cidade, email, curso, periodo FROM alunos WHERE id = :id");
$stmt->execute(["id" => $id]);
$aluno = $stmt->fetch();

if (!$aluno) {
    exit("Aluno não encontrado.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Alunos</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="card card-larga">
    <h1>Editar aluno</h1>

    <section>
        <h2>Editar aluno</h2>
        <form action="atualizar.php" method="POST">
            <input type="hidden" name="id" value="<?= (int) $aluno["id"] ?>">

            <label for="nome">Nome</label>
            <input id="nome" name="nome" type="text"
                value="<?= htmlspecialchars($aluno["nome"]) ?>" required>

            <label for="idade">Idade</label>
            <input id="idade" name="idade" type="number" min="1"
                value="<?= htmlspecialchars((string) $aluno["idade"]) ?>" required>

            <label for="cidade">Cidade</label>
            <input id="cidade" name="cidade" type="text"
                value="<?= htmlspecialchars($aluno["cidade"]) ?>" required>

            <label for="email">Email</label>
            <input id="email" name="email" type="email"
                value="<?= htmlspecialchars($aluno["email"]) ?>" required>

            <label for="curso">Curso</label>
            <input id="curso" name="curso" type="text"
                value="<?= htmlspecialchars($aluno["curso"]) ?>" required>

            <label for="periodo">Período</label>
            <select id="periodo" name="periodo" required>
                <option value="Manhã" <?= $aluno["periodo"] === "Manhã" ? "selected" : "" ?>>Manhã</option>
                <option value="Tarde" <?= $aluno["periodo"] === "Tarde" ? "selected" : "" ?>>Tarde</option>
                <option value="Noite" <?= $aluno["periodo"] === "Noite" ? "selected" : "" ?>>Noite</option>
            </select>

            <button type="submit">Salvar alterações</button>
        </form>
    </section>
    <p><a href="consultar.php">Cancelar</a></p>
</main>
</body>
</html>