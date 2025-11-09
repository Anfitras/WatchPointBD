<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Consulta de Obras</title>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 8px;
        }

        .search-form {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <?php
    require_once "..\BD\conexaoBD.php";

    $termo_busca = $_GET['busca'] ?? '';

    $params = [];

    $sql = "
        SELECT 
            obras.*, 
            GROUP_CONCAT(generos.nome SEPARATOR ', ') AS generos_da_obra
        FROM 
            obras
        LEFT JOIN 
            obras_generos ON obras.id = obras_generos.id_obra
        LEFT JOIN 
            generos ON obras_generos.id_genero = generos.id
    ";

    if (!empty($termo_busca)) {
        $sql .= " WHERE obras.nome LIKE :busca ";
        $params[':busca'] = '%' . $termo_busca . '%';
    }

    $sql .= "
        GROUP BY 
            obras.id
        ORDER BY 
            obras.nome
    ";

    $stmt = $conexao->prepare($sql);
    $stmt->execute($params);
    $registros = $stmt->fetchAll();
    ?>
    <main>
        <h1>Lista de Obras</h1>

        <form method="GET" action="consulta_obras.php" class="search-form">
            <label for="busca">Buscar por Nome:</label>
            <input type="text" id="busca" name="busca" value="<?= htmlspecialchars($termo_busca) ?>">

            <button type="submit">Buscar</button>

            <a href="consulta_obras.php">Limpar Filtro</a>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Duração/Episódios</th>
                    <th>Nota</th>
                    <th>Gêneros</th>
                    <th>Editar</th>
                    <th>Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $r) { ?>
                    <tr>
                        <td><?= htmlspecialchars($r['nome']) ?></td>
                        <td><?= htmlspecialchars($r['tipo']) ?></td>
                        <td><?= htmlspecialchars($r['duracao_ou_episodios']) ?></td>
                        <td><?= htmlspecialchars($r['nota']) ?></td>
                        <td><?= htmlspecialchars($r['generos_da_obra'] ?? 'Nenhum') ?></td>
                        <td><a href="editar_obras.php?id=<?= $r['id'] ?>">Editar</a></td>
                        <td><a href="excluir_obras.php?id=<?= $r['id'] ?>"
                                onclick="return confirm('Tem certeza?');">Excluir</a></td>
                    </tr>
                <?php } ?>

                <?php if (empty($registros) && !empty($termo_busca)): ?>
                    <tr>
                        <td colspan="7">Nenhuma obra encontrada com o nome "<?= htmlspecialchars($termo_busca) ?>".</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <br>
        <a href="../index.html">Voltar para a página inicial</a>
    </main>
</body>

</html>