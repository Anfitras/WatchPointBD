<?php
require_once "..\BD\conexaoBD.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $url_poster = $_POST['url_poster'] ?? '';
    $sinopse = $_POST['sinopse'] ?? '';
    $duracao_ou_episodios = $_POST['duracao_ou_episodios'] ?? '';
    $nota = $_POST['nota'] ?? null;

    $generos_selecionados = $_POST['generos'] ?? [];

    $conexao->beginTransaction();

    try {
        $sql_obra = "INSERT INTO obras (nome, tipo, url_poster, sinopse, duracao_ou_episodios, nota) 
                     VALUES (:nome, :tipo, :url_poster, :sinopse, :duracao_ou_episodios, :nota)";
        $stmt_obra = $conexao->prepare($sql_obra);
        $stmt_obra->execute([
            ':nome' => $nome,
            ':tipo' => $tipo,
            ':url_poster' => $url_poster,
            ':sinopse' => $sinopse,
            ':duracao_ou_episodios' => $duracao_ou_episodios,
            ':nota' => $nota
        ]);

        $id_nova_obra = $conexao->lastInsertId();

        if (!empty($generos_selecionados)) {
            $sql_genero = "INSERT INTO obras_generos (id_obra, id_genero) VALUES (:id_obra, :id_genero)";
            $stmt_genero = $conexao->prepare($sql_genero);

            foreach ($generos_selecionados as $id_genero) {
                $stmt_genero->execute([
                    ':id_obra' => $id_nova_obra,
                    ':id_genero' => $id_genero
                ]);
            }
        }

        $conexao->commit();

        echo "<p>Obra cadastrada com sucesso!</p>";
        echo '<a href="consulta_obras.php">Ver lista de obras</a>';

    } catch (PDOException $e) {
        $conexao->rollBack();
        echo "Erro ao cadastrar obra: " . $e->getMessage();
    }
} else {
    echo "<p>Requisição inválida.</p>";
}
?>