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
    </style>
</head>

<body>
    <?php
    require_once "..\BD\conexaoBD.php";
    $stmt = $conexao->query("SELECT id, email, senha, data_nascimento FROM usuario");
    $registros = $stmt->fetchAll();
    ?>
    <main>
        <h1>Lista de Usuários</h1>
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
            </tbody>
        </table>
        <br>
        <a href="../index.html">Voltar para a página inicial</a>
    </main>
</body>

</html>