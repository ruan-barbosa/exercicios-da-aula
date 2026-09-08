<?php

$host = "localhost";
$porta = 3306;
$banco = "biblioteca";
$usuario = "root";
$senha = "";

try {
    $pdo = new PDO("mysql:host=$host;port=$porta;dbname=$banco;charset=utf8mb4", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $erro) {
    echo "Erro de conexão: " . $erro->getMessage();
    exit;
}

function buscarLeitor(PDO $pdo, int $id)
{
    $sql = " 
        SELECT id, nome, ativo
        FROM leitores 
        WHERE id = :id
        ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id" => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function buscarLivro(PDO $pdo, int $id)
{
    $stmt = $pdo->prepare("
        SELECT id, titulo, disponivel 
        FROM livros
        WHERE id = :id 
        ");

    $stmt->execute([":id" => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Simulando os IDs para não dar erro de variável indefinida
$leitorId = 1;
$livroId = 1;

$leitor = buscarLeitor($pdo, $leitorId);
$livro = buscarLivro($pdo, $livroId);

if (!$leitor || (int) $leitor["ativo"] !== 1) {
    throw new Exception("Leitor inexistente ou inativo.");
}

if (!$livro || (int) $livro["disponivel"] !== 1) {
    throw new Exception("Livro inexistente ou indisponivel.");
}

$dataEmprestimo = date("Y-m-d");
$dataPrevista = date(
    "Y-m-d",
    strtotime("+7 days", strtotime($dataEmprestimo))
);

$pdo->beginTransaction();
try {
    $sql = " 
    INSERT INTO emprestimos
    (leitor_id, livro_id, data_emprestimo, data_prevista) 
    VALUES (:leitor, :livro, :emprestimo, :prevista) 
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ":leitor" => $leitor["id"],
        ":livro" => $livro["id"],
        ":emprestimo" => $dataEmprestimo,
        ":prevista" => $dataPrevista
    ]);

    $sql = " 
    UPDATE livros 
    SET disponivel = 0 
    WHERE id = :id ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id" => $livro["id"]]);

    $pdo->commit();
} catch (Exception $erro) {
    $pdo->rollBack();
    throw $erro;
}


// Simulando o ID para a devolução não dar erro
$emprestimoId = 1;

$stmt = $pdo->prepare(" 
    SELECT livro_id, status 
    FROM emprestimos 
    WHERE id = :id 
");
$stmt->execute([":id" => $emprestimoId]);
$emprestimo = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$emprestimo || $emprestimo["status"] !== "Emprestado") {
    throw new Exception("Emprestimo invalido.");
}

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare(" 
        UPDATE emprestimos 
        SET data_devolucao = :data, status = 'Devolvido' 
        WHERE id = :id
    ");
    $stmt->execute([
        ":data" => date("Y-m-d"),
        ":id"   => $emprestimoId
    ]);

    $stmt = $pdo->prepare(" 
        UPDATE livros 
        SET disponivel = 1 
        WHERE id = :id 
    ");
    $stmt->execute([
        ":id" => $emprestimo["livro_id"]
    ]);

    $pdo->commit();
} catch (Exception $erro) {
    $pdo->rollBack();
    throw $erro;
}

$sql = " 
    SELECT 
        e.id, 
        l.nome AS leitor, 
        b.titulo AS livro, 
        c.nome AS categoria, 
        e.data_emprestimo, 
        e.data_prevista, 
        e.data_devolucao, 
        e.status 
    FROM emprestimos e 
    INNER JOIN leitores l ON e.leitor_id = l.id
    INNER JOIN livros b ON e.livro_id = b.id
    INNER JOIN categorias c ON b.categoria_id = c.id
    ORDER BY e.id DESC 
";

$lista = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

foreach ($lista as $item) {
    if ($item["status"] === "Devolvido") {
        $situacao = "Devolvido";
    } elseif ($item["data_prevista"] < date("Y-m-d")) {
        $situacao = "Atrasado";
    } else {
        $situacao = "Em dia";
    }

    echo "Leitor: " . $item["leitor"] . "\n";
    echo "Livro: " . $item["livro"] . "\n";
    echo "Categoria: " . $item["categoria"] . "\n";
    echo "Situacao: " . $situacao . "\n\n";
}