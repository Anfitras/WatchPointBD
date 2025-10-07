<?php
require_once "../BD/conexaoBD.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $data_nascimento = $_POST['data_nascimento'];

    $sql = "UPDATE usuario SET email = :email, data_nascimento = :data_nascimento WHERE id = :id";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([':email' => $email, ':data_nascimento' => $data_nascimento, ':id' => $id]);

    // Opcional: Atualizar senha
    if (!empty($_POST['senha'])) {
        $senha_hash = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $stmt_senha = $conexao->prepare("UPDATE usuario SET senha = :senha WHERE id = :id");
        $stmt_senha->execute([':senha' => $senha_hash, ':id' => $id]);
    }
    header("Location: consulta_usuario.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conexao->prepare("SELECT * FROM usuario WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $registro = $stmt->fetch();
} else {
    echo "ID não fornecido.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
</head>

<body>
    <main>
        <h1>Editar Usuário</h1>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $registro['id'] ?>">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required
                value="<?= htmlspecialchars($registro['email']) ?>"><br><br>

            <label for="data_nascimento">Data de Nascimento:</label>
            <input type="date" id="data_nascimento" name="data_nascimento" required
                value="<?= htmlspecialchars($registro['data_nascimento']) ?>"><br><br>

            <label for="senha">Nova Senha (deixe em branco para não alterar):</label>
            <input type="password" id="senha" name="senha"><br><br>

            <button type="submit">Salvar Alterações</button>
        </form>
    </main>
</body>

</html>