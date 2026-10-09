<?php
require_once __DIR__ . "/tmdb.php";

// Busca os detalhes de vários filmes ao mesmo tempo (mais rápido)
function tmdb_lote(array $ids): array {
    $mh = curl_multi_init();
    $handles = [];

    foreach ($ids as $id) {
        $ch = curl_init("https://api.themoviedb.org/3/movie/$id?language=pt-BR");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . TMDB_TOKEN,
                "accept: application/json",
            ],
        ]);
        curl_multi_add_handle($mh, $ch);
        $handles[$id] = $ch;
    }

    do {
        $status = curl_multi_exec($mh, $executando);
        if ($executando) {
            curl_multi_select($mh);
        }
    } while ($executando && $status === CURLM_OK);

    $resultado = [];
    foreach ($handles as $id => $ch) {
        $resultado[$id] = json_decode(curl_multi_getcontent($ch), true) ?: [];
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }
    curl_multi_close($mh);

    return $resultado;
}

// Importa os filmes populares (cada página = até 20 filmes). Retorna quantos entraram.
function sincronizar_filmes(PDO $pdo, int $paginas = 3): int {
    set_time_limit(120);

    $existentes = $pdo->query("SELECT tmdb_id FROM filmes WHERE tmdb_id IS NOT NULL")
                      ->fetchAll(PDO::FETCH_COLUMN);

    $insere = $pdo->prepare(
        "INSERT IGNORE INTO filmes (titulo, duracao, sinopse, tmdb_id, poster) VALUES (?,?,?,?,?)"
    );

    $novos = 0;

    for ($p = 1; $p <= $paginas; $p++) {
        $lista = tmdb("/movie/popular", ["page" => $p])["results"] ?? [];

        $ids = [];
        foreach ($lista as $item) {
            if (!in_array($item["id"], $existentes)) {
                $ids[] = $item["id"];
            }
        }
        if (!$ids) { continue; }

        foreach (tmdb_lote($ids) as $d) {
            if (empty($d["title"])) { continue; }

            $insere->execute([
                mb_substr($d["title"], 0, 150),
                (int)($d["runtime"] ?? 0),
                !empty($d["overview"]) ? $d["overview"] : "Sem sinopse disponível.",
                $d["id"],
                $d["poster_path"] ?? null,
            ]);
            $novos += $insere->rowCount();
        }
    }

    return $novos;
}