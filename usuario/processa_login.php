<?php
require_once "../BD/conexaoBD.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $isAjax = isset($_POST['ajax']) && $_POST['ajax'] == '1';

    try {
        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email = :email AND senha = :senha");
        $stmt->execute([':email' => $email, ':senha' => $senha]);
        $usuario = $stmt->fetch();

        if ($usuario) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => true]);
                exit;
            } else {
                header("Location: ../index.html");
                exit;
            }
        } else {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['success' => false, 'message' => 'Email ou senha inválidos.']);
                exit;
            } else {
                header("Location: ../login.html?erro=1");
                exit;
            }
        }
    } catch (PDOException $e) {
        echo "Erro no login: " . $e->getMessage();
    }
}
?>