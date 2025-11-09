<?php
require_once "../BD/conexaoBD.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $url_poster = $_POST['url_poster'];
    $sinopse = $_POST['sinopse'];
    $duracao_ou_episodios = $_POST['duracao_ou_episodios'];
    $nota = $_POST['nota'];
    $generos_selecionados = $_POST['generos'] ?? [];

    $conexao->beginTransaction();
    try {
        $sql_obra = "UPDATE obras SET nome = :nome, tipo = :tipo, url_poster = :url_poster, sinopse = :sinopse, 
                     duracao_ou_episodios = :duracao_ou_episodios, nota = :nota WHERE id = :id";
        $stmt_obra = $conexao->prepare($sql_obra);
        $stmt_obra->execute([
            ':id' => $id,
            ':nome' => $nome,
            ':tipo' => $tipo,
            ':url_poster' => $url_poster,
            ':sinopse' => $sinopse,
            ':duracao_ou_episodios' => $duracao_ou_episodios,
            ':nota' => $nota
        ]);

        $stmt_delete_generos = $conexao->prepare("DELETE FROM obras_generos WHERE id_obra = :id_obra");
        $stmt_delete_generos->execute([':id_obra' => $id]);

        if (!empty($generos_selecionados)) {
            $sql_genero = "INSERT INTO obras_generos (id_obra, id_genero) VALUES (:id_obra, :id_genero)";
            $stmt_genero = $conexao->prepare($sql_genero);
            foreach ($generos_selecionados as $id_genero) {
                $stmt_genero->execute([
                    ':id_obra' => $id,
                    ':id_genero' => $id_genero
                ]);
            }
        }

        $conexao->commit();
        header("Location: consulta_obras.php");
        exit;

    } catch (PDOException $e) {
        $conexao->rollBack();
        echo "Erro ao atualizar a obra: " . $e->getMessage();
        exit;
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt_obra = $conexao->prepare("SELECT * FROM obras WHERE id = :id");
    $stmt_obra->execute([':id' => $id]);
    $registro = $stmt_obra->fetch();

    if (!$registro) {
        echo "Obra não encontrada.";
        exit;
    }

    $generos_disponiveis = $conexao->query("SELECT * FROM generos ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

    $stmt_selecionados = $conexao->prepare("SELECT id_genero FROM obras_generos WHERE id_obra = :id");
    $stmt_selecionados->execute([':id' => $id]);

    $generos_selecionados_ids = $stmt_selecionados->fetchAll(PDO::FETCH_COLUMN);

} else {
    echo "ID não fornecido.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Obra</title>
</head>

<body>
    <main>
        <h1>Editar Obra</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $registro['id'] ?>">
            <label for="nome">Nome da Obra:</label>
            <input type="text" id="nome" name="nome" required
                value="<?= htmlspecialchars($registro['nome']) ?>"><br><br>

            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" onchange="atualizarCampoDinamico()">
                <option value="Filme" <?= $registro['tipo'] === 'Filme' ? 'selected' : '' ?>>Filme</option>
                <option value="Série" <?= $registro['tipo'] === 'Série' ? 'selected' : '' ?>>Série</option>
                <option value="Anime" <?= $registro['tipo'] === 'Anime' ? 'selected' : '' ?>>Anime</option>
            </select><br><br>

            <label for="url_poster">URL do Poster:</label>
            <input type="text" id="url_poster" name="url_poster"
                value="<?= htmlspecialchars($registro['url_poster']) ?>"><br><br>

            <label for="sinopse">Sinopse:</label>
            <textarea id="sinopse" name="sinopse" rows="4"
                cols="50"><?= htmlspecialchars($registro['sinopse']) ?></textarea><br><br>

            <label for="duracao_ou_episodios" id="label_dinamica">Duração/Episódios:</label>
            <input type="text" id="duracao_ou_episodios" name="duracao_ou_episodios" required
                value="<?= htmlspecialchars($registro['duracao_ou_episodios']) ?>"><br><br>

            <label for="nota">Nota (0 a 10):</label>
            <input type="number" id="nota" name="nota" step="0.1" min="0" max="10"
                value="<?= htmlspecialchars($registro['nota']) ?>"><br><br>

            <fieldset>
                <legend>Gêneros:</legend>
                <?php foreach ($generos_disponiveis as $genero): ?>
                    <?php
                    $checked = in_array($genero['id'], $generos_selecionados_ids) ? 'checked' : '';
                    ?>
                    <label style="display: block; margin-bottom: 5px;">
                        <input type="checkbox" name="generos[]" value="<?= $genero['id'] ?>" <?= $checked ?> />
                        <?= htmlspecialchars($genero['nome']) ?>
                    </label>
                <?php endforeach; ?>
            </fieldset>
            <br />

            <button type="submit">Salvar Alterações</button>
        </form>
    </main>

    <script>
        function atualizarCampoDinamico() {
            const tipoSelect = document.getElementById('tipo');
            const labelDinamica = document.getElementById('label_dinamica');
            const inputDinamico = document.getElementById('duracao_ou_episodios');

            if (tipoSelect.value === 'Filme') {
                labelDinamica.textContent = 'Duração do Filme:';
                inputDinamico.placeholder = 'Ex: 2h 15m';
            } else {
                labelDinamica.textContent = 'Quantidade de Episódios:';
                inputDinamico.placeholder = 'Ex: 24';
            }
        }
        document.addEventListener('DOMContentLoaded', atualizarCampoDinamico);
    </script>

</body>

</html>