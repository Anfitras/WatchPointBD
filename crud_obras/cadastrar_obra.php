<?php
require_once "../BD/conexaoBD.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $url_poster = $_POST['url_poster'] ?? '';
    $sinopse = $_POST['sinopse'] ?? '';
    $episodios = $_POST['episodios'] ?? null;
    $nota = !empty($_POST['nota']) ? $_POST['nota'] : null;
    $generos = $_POST['generos'] ?? null;

    try {
        $sql = "INSERT INTO obras (nome, tipo, url_poster, sinopse, episodios, nota, generos) VALUES (:nome, :tipo, :url_poster, :sinopse, :episodios, :nota, :generos)";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':nome' => $nome,
            ':tipo' => $tipo,
            ':url_poster' => $url_poster,
            ':sinopse' => $sinopse,
            ':episodios' => $episodios,
            ':nota' => $nota,
            ':generos' => $generos
        ]);
        header("Location: ../crud.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao cadastrar obra: " . $e->getMessage();
    }
}
?>