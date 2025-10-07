<?php
require_once "..\BD\conexaoBD.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $url_poster = $_POST['url_poster'] ?? '';
    $sinopse = $_POST['sinopse'] ?? '';
    $duracao_ou_episodios = $_POST['duracao_ou_episodios'] ?? '';
    $nota = $_POST['nota'] ?? null;
    $generos = $_POST['generos'] ?? '';

    try {
        $sql = "INSERT INTO obras (nome, tipo, url_poster, sinopse, duracao_ou_episodios, nota, generos) 
                VALUES (:nome, :tipo, :url_poster, :sinopse, :duracao_ou_episodios, :nota, :generos)";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':tipo' => $tipo,
            ':url_poster' => $url_poster,
            ':sinopse' => $sinopse,
            ':duracao_ou_episodios' => $duracao_ou_episodios,
            ':nota' => $nota,
            ':generos' => $generos
        ]);
        echo "<p>Obra cadastrada com sucesso!</p>";
        echo '<a href="consulta_obras.php">Ver lista de obras</a>';
    } catch (PDOException $e) {
        echo "Erro ao cadastrar obra: " . $e->getMessage();
    }
} else {
    echo "<p>Requisição inválida.</p>";
}
?>