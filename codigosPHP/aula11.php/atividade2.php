<?php

$notas = [8.5, 6.0, 4.5, 9.0, 7.0];

function verificarSituacao($nota) {
    if ($nota >= 7) {
        return "Aprovado";
    } elseif ($nota >= 5 and $nota < 7) {
        return "Recuperação";
    } else {
        return "Reprovado";
    }
}

$aprovado = 0;
$recuperacao = 0;
$reprovado = 0;

foreach ($notas as $i => $nota) {
    $numero_aluno = $i + 1;
    $situacao = verificarSituacao($nota);
    echo "Aluno - $numero_aluno Nota: $nota - $situacao <br>";

    if ($situacao == "Aprovado") {
        $aprovado++;
    } elseif ($situacao == "Recuperação") {
        $recuperacao++;
    } else {
        $reprovado++;
    }
}
echo "<br>";
echo "Resumo:<br>Aprovado(s): $aprovado<br>Recuperação: $recuperacao<br>Reprovado(s): $reprovado";