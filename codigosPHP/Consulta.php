<?php
    //SEM FILTRO
    try {
        //DSN (Data Source Name)
        $pdo = new PDO("mysql:host=localhost;port=3306;dbname=cadastro;charset=utf8", "root", "");

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //seta o que mudar e depois o que mudar 

        $sql = "SELECT nome, email FROM usuario";
        $stmt = $pdo->query($sql);
        //Direção da resposta
        //var_dump($stmt);

        $alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //retorna um array associativo (Uma dicionário de dicionários)

        foreach ($alunos as $chave => $aluno) {
            echo $chave . "- " .  "Nome: " . $aluno['nome'] . " | Email: " . $aluno['email'] . "<br>";
        }

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    };

    echo "<br>";
    echo "O aluno do ID 3: ";

    //COM FILTRO
    try {
        $pdo = new PDO("mysql:host=localhost;port=3306;dbname=cadastro;charset=utf8", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $id = 3; 

        $sql = "SELECT nome, email FROM usuario WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($aluno) {
            echo "Nome: " . $aluno['nome'] . " | Email: " . $aluno['email'];
        } else {
            echo "Nenhum usuário encontrado com esse ID.";
        }

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
?>