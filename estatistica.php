<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Estatísticas - WatchPoint</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        h1,
        h2 {
            border-bottom: 1px solid #ccc;
        }

        table {
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <main>
        <h1>Painel de Estatísticas</h1>

        <?php
        require_once "bd/conexaoBD.php";

        $sql_tipo = "
            SELECT 
                tipo, 
                COUNT(*) as total_de_obras
            FROM 
                obras 
            GROUP BY 
                tipo
            ORDER BY
                total_de_obras DESC
        ";
        $stmt_tipo = $conexao->query($sql_tipo);
        $obras_por_tipo = $stmt_tipo->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <h2>Total de Obras por Tipo</h2>
        <table>
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Total de Obras</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($obras_por_tipo as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['tipo']) ?></td>
                        <td><?= $item['total_de_obras'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
        $sql_genero = "
    SELECT 
        g.nome, 
        COUNT(og.id_obra) as total_de_obras
    FROM 
        generos g
    LEFT JOIN 
        obras_generos og ON g.id = og.id_genero
    GROUP BY 
        g.id
    ORDER BY
        total_de_obras DESC, g.nome ASC
";
        $stmt_genero = $conexao->query($sql_genero);
        $obras_por_genero = $stmt_genero->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <h2>Total de Obras por Gênero</h2>
        <table>
            <thead>
                <tr>
                    <th>Gênero</th>
                    <th>Total de Obras</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($obras_por_genero as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome']) ?></td>
                        <td><?= $item['total_de_obras'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <br>
        <a href="index.html">Voltar para a página inicial</a>
    </main>
</body>

</html>