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
    </style>
</head>

<body>
    <?php
    require_once "..\BD\conexaoBD.php";
    $stmt = $conexao->query("SELECT * FROM obras");
    $registros = $stmt->fetchAll();
    ?>
    <main>
        <h1>Lista de Obras</h1>
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
                        <td><?= htmlspecialchars($r['generos']) ?></td>
                        <td><a href="editar_obras.php?id=<?= $r['id'] ?>">Editar</a></td>
                        <td><a href="excluir_obras.php?id=<?= $r['id'] ?>"
                                onclick="return confirm('Tem certeza?');">Excluir</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
        <a href="../index.html">Voltar para a página inicial</a>
    </main>
</body>

</html>