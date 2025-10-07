<?php
require_once "../BD/conexaoBD.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $conexao->prepare("DELETE FROM obras WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header("Location: ../crud.php");
        exit;
    } catch (PDOException $e) {
        echo "Erro ao excluir o registro: " . $e->getMessage();
    }
} else {
    echo "ID não fornecido.";
}
?>