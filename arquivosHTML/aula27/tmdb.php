<?php
require_once __DIR__ . "/config.php";

function tmdb(string $caminho, array $params = []): array {
    $params["language"] = "pt-BR";
    $url = "https://api.themoviedb.org/3" . $caminho . "?" . http_build_query($params);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . TMDB_TOKEN,
            "accept: application/json",
        ],
    ]);
    $resposta = curl_exec($ch);
    curl_close($ch);

    return json_decode($resposta, true) ?? [];
}