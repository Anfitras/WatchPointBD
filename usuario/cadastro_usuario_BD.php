<?php
require_once "..\BD\conexaoBD.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $data_nascimento = $_POST['data_nascimento'] ?? '';

    // É crucial fazer o hash da senha antes de salvar no banco de dados.
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO usuario (email, senha, data_nascimento) VALUES (:email, :senha, :data_nascimento)";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':senha' => $senha_hash,
            ':data_nascimento' => $data_nascimento
        ]);
        echo "<p>Usuário cadastrado com sucesso!</p>";
        echo '<a href="consulta_usuario.php">Ver lista de usuários</a>';
    } catch (PDOException $e) {
        echo "Erro ao cadastrar usuário: " . $e->getMessage();
    }
} else {
    echo "<p>Requisição inválida.</p>";
}
?>