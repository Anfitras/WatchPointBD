<?php
require_once "..\BD\conexaoBD.php";
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conexao->prepare("DELETE FROM usuario WHERE id = :id");
    if ($stmt->execute([':id' => $id])) {
        header("Location: consulta_usuario.php");
        exit;
    } else {
        echo "Erro ao excluir o registro.";
    }
} else {
    echo "ID não fornecido.";
}
?>