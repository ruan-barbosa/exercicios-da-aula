<?php

$alunos = [ 
    ["nome" => "Ana", "nota1" => 8.0, "nota2" => 9.0, "frequencia" => 90],
    ["nome" => "Carlos", "nota1" => 5.0, "nota2" => 6.0, "frequencia" => 80], 
    ["nome" => "Mariana", "nota1" => 9.5, "nota2" => 8.5, "frequencia" => 70] 
];

function calcularMedia($nota1, $nota2) {
    return $media = ($nota1 + $nota2) / 2;
}

function verificarSituacao($media, $frequencia) {
    if ($media >= 7 and $frequencia >= 75) {
        return "Aprovado";
    } elseif ($media >= 5 and $frequencia >= 75) {
        return "Recuperação";
    } else {
        return "Reprovado";
    }
}

$somaDasMedias = 0;
$totalAprovados = 0;
$maiorMedia = 0;
$totalAlunos = count($alunos);

foreach($alunos as $aluno) {
    $media = calcularMedia($aluno['nota1'], $aluno['nota2']);
    $situacao = verificarSituacao($media, $aluno['frequencia']);
    
    echo "Nome: {$aluno['nome']} - Média: $media - Frequência: {$aluno['frequencia']}% - Situação: $situacao <br>";
    
    $somaDasMedias += $media;
    
    if ($situacao == "Aprovado") {
        $totalAprovados++;
    }if ($media > $maiorMedia) {
        $maiorMedia = $media;
    }
}

$mediaGeral = $somaDasMedias / $totalAlunos;

echo "--- Resumo da Turma ---<br>";
echo "Média geral da turma: " . number_format($mediaGeral, 2) . "<br>";
echo "Total de aprovados: $totalAprovados<br>";
echo "Maior média encontrada: $maiorMedia";