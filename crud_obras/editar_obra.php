<?php
require_once "../BD/conexaoBD.php";

$obra = null;
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conexao->prepare("SELECT * FROM obras WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $obra = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $url_poster = $_POST['url_poster'];
    $sinopse = $_POST['sinopse'];
    $episodios = $_POST['episodios'];
    $nota = !empty($_POST['nota']) ? $_POST['nota'] : null;
    $generos = $_POST['generos'];

    $stmt = $conexao->prepare("UPDATE obras SET nome = :nome, tipo = :tipo, url_poster = :url_poster, sinopse = :sinopse, episodios = :episodios, nota = :nota, generos = :generos WHERE id = :id");
    $stmt->execute(compact('nome', 'tipo', 'url_poster', 'sinopse', 'episodios', 'nota', 'generos', 'id'));

    header("Location: ../crud.php");
    exit;
}

if (!$obra) {
    die("Obra não encontrada.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Editar Obra</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/crud.css" />
</head>

<body>
    <h1 id="titulo_principal">Editar Obra: <?= htmlspecialchars($obra['nome']) ?></h1>
    <main style="justify-content: center;">
        <div id="janela-add">
            <form class="form" method="POST">
                <input type="hidden" name="id" value="<?= $obra['id'] ?>">

                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($obra['nome']) ?>" required />

                <label for="tipo">Tipo:</label>
                <select id="tipo" name="tipo" required>
                    <option value="Anime" <?= $obra['tipo'] === 'Anime' ? 'selected' : '' ?>>Anime</option>
                    <option value="Filme" <?= $obra['tipo'] === 'Filme' ? 'selected' : '' ?>>Filme</option>
                    <option value="Série" <?= $obra['tipo'] === 'Série' ? 'selected' : '' ?>>Série</option>
                </select>

                <label for="poster">URL do Pôster:</label>
                <input type="url" id="poster" name="url_poster" value="<?= htmlspecialchars($obra['url_poster']) ?>"
                    required />

                <label for="sinopse">Sinopse:</label>
                <textarea id="sinopse" name="sinopse" rows="3"
                    required><?= htmlspecialchars($obra['sinopse']) ?></textarea>

                <label for="episodios">Episódios:</label>
                <input type="text" id="episodios" name="episodios"
                    value="<?= htmlspecialchars($obra['episodios']) ?>" />

                <label for="nota">Nota (0 a 10):</label>
                <input type="number" id="nota" name="nota" value="<?= htmlspecialchars($obra['nota']) ?>" step="0.1" />

                <label for="generos">Gêneros (separe por vírgula):</label>
                <input type="text" id="generos" name="generos" value="<?= htmlspecialchars($obra['generos']) ?>" />

                <button type="submit">Salvar Alterações</button>
            </form>
        </div>
    </main>
</body>

</html>