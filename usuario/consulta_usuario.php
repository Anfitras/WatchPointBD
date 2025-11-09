<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Consulta de Usuários</title>
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

    $sql = "SELECT id, email, senha, data_nascimento FROM usuario";

    if (!empty($termo_busca)) {
        $sql .= " WHERE email LIKE :busca ";
        $params[':busca'] = '%' . $termo_busca . '%';
    }

    $sql .= " ORDER BY email";

    $stmt = $conexao->prepare($sql);
    $stmt->execute($params);
    $registros = $stmt->fetchAll();
    ?>
    <main>
        <h1>Lista de Usuários</h1>

        <form method="GET" action="consulta_usuario.php" class="search-form">
            <label for="busca">Buscar por Email:</label>
            <input type="text" id="busca" name="busca" value="<?= htmlspecialchars($termo_busca) ?>">

            <button type="submit">Buscar</button>

            <a href="consulta_usuario.php">Limpar Filtro</a>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Senha</th>
                    <th>Data de Nascimento</th>
                    <th>Editar</th>
                    <th>Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registros as $r) { ?>
                    <tr>
                        <td><?= htmlspecialchars($r['email']) ?></td>
                        <td><?= htmlspecialchars($r['senha']) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($r['data_nascimento']))) ?></td>
                        <td><a href="editar_usuario.php?id=<?= $r['id'] ?>">Editar</a></td>
                        <td><a href="excluir_usuario.php?id=<?= $r['id'] ?>"
                                onclick="return confirm('Tem certeza?');">Excluir</a></td>
                    </tr>
                <?php } ?>

                <?php if (empty($registros) && !empty($termo_busca)): ?>
                    <tr>
                        <td colspan="5">Nenhum usuário encontrado com o email "<?= htmlspecialchars($termo_busca) ?>".</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <br>
        <a href="../index.html">Voltar para a página inicial</a>
    </main>
</body>

</html>