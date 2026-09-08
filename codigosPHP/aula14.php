<?php

$alunos = [
    ["nome" => "Ana", "nota" => "8.5"],
    ["nome" => "Carlos", "nota" => "6.0"],
    ["nome" => "Mariana", "nota" => "9.0"]
];

function verificarSituacao($nota) {
    if ($nota >= 7) {
        return "Aprovado";
    }elseif ($nota >= 5 and $nota < 7) {
        return "Recuperação";
    }else {
        return "Reprovado";
    }
};

$host = "localhost";
$port = 3306;
$dbname = "escola";
$usuario = "root";
$senha = "";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexão realizada com sucesso!<br><br>";

    $sql = "
        INSERT INTO alunos (nome, nota)
        VALUES (:nome, :nota)
        ";

    $stmt = $pdo->prepare($sql);

    $pdo->exec("TRUNCATE TABLE alunos");

    foreach ($alunos as $aluno) {
        $stmt->execute([
            ':nome' => $aluno['nome'],
            ':nota' => $aluno['nota']
        ]);
    }

    $sqlSelect = "
        SELECT id, nome, nota
        FROM alunos
        ORDER BY id
    ";
    $stmtSelect = $pdo->query($sqlSelect);
    $registros = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);

    foreach ($registros as $registro) {
        $situacao = verificarSituacao($registro['nota']);
        echo "ID: {$registro['id']}<br>Aluno: {$registro['nome']}<br>Nota: {$registro['nota']}<br>Situação: {$situacao}<br>";
        echo "--------------------------<br>";
    }

} catch (PDOException $e) {
    echo "Erro na conexão" . $e->getMessage();
};