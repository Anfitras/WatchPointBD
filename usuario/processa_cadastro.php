<?php
require_once "../BD/conexaoBD.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $data_nascimento = $_POST['nascimento'] ?? null;

    if (empty($email) || empty($senha)) {
        echo "Email e senha são obrigatórios.";
        exit;
    }

    try {
        $sql = "INSERT INTO usuarios (email, senha, data_nascimento) VALUES (:email, :senha, :data_nascimento)";
        $stmt = $conexao->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':senha' => $senha,
            ':data_nascimento' => $data_nascimento
        ]);

        header("Location: ../login.html");
        exit;
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            echo "Erro ao cadastrar: Este e-mail já está em uso.";
        } else {
            echo "Erro ao cadastrar: " . $e->getMessage();
        }
    }
} else {
    echo "Requisição inválida.";
}
?>