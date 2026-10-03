<?php
require "conexao.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
$nome = trim($_GET["nome"] ?? "");

if ($id) {
    $sql = "SELECT id, nome, idade, cidade, email, curso, periodo, criado_em
            FROM alunos
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["id" => $id]);
} elseif ($nome !== "") {
    $sql = "SELECT id, nome, idade, cidade, email, curso, periodo, criado_em
            FROM alunos
            WHERE nome LIKE :nome
            ORDER BY nome";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["nome" => "%" . $nome . "%"]);
} else {
    $sql = "SELECT id, nome, idade, cidade, email, curso, periodo, criado_em
            FROM alunos
            ORDER BY id";
    $stmt = $pdo->query($sql);
}

$alunos = $stmt->fetchAll();
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
    <h1>Alunos cadastrados</h1>

    <?php if (!$alunos): ?>
        <p>Nenhum aluno encontrado.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Idade</th>
                    <th>Cidade</th>
                    <th>E-mail</th>
                    <th>Curso</th>
                    <th>Período</th>
                    <th>Criado em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php  foreach ($alunos as $aluno): ?>
                    <tr>
                        <td><?= htmlspecialchars((string) $aluno["id"]) ?></td>
                        <td><?= htmlspecialchars($aluno["nome"]) ?></td>
                        <td><?= htmlspecialchars($aluno["idade"]) ?></td>
                        <td><?= htmlspecialchars($aluno["cidade"]) ?></td>
                        <td><?= htmlspecialchars($aluno["email"]) ?></td>
                        <td><?= htmlspecialchars($aluno["curso"]) ?></td>
                        <td><?= htmlspecialchars($aluno["periodo"]) ?></td>
                        <td><?= htmlspecialchars($aluno["criado_em"]) ?></td>
                        <td>
                            <a href="editar.php?id=<?= (int) $aluno["id"] ?>">Editar</a>

                            <form action="deletar.php" method="POST"
                                onsubmit="return confirm('Deseja realmente excluir este aluno?')">
                                <input type="hidden" name="id" value="<?= (int) $aluno["id"] ?>">
                                <button type="submit">Excluir</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="index.html">Voltar</a></p>
</main>
</body>
</html>