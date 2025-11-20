<?php
require_once __DIR__ . '/../bd/conexaoBD.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Requisição inválida.";
    exit;
}

$id_usuario = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;
$id_obra = isset($_POST['id_obra']) ? intval($_POST['id_obra']) : 0;
$nota = isset($_POST['nota']) ? floatval($_POST['nota']) : null;
$comentario = $_POST['comentario'] ?? '';

if ($id_usuario <= 0 || $id_obra <= 0 || $nota === null) {
    echo "Dados incompletos.";
    exit;
}

try {
    $stmt = $conexao->prepare("SELECT id FROM usuario WHERE id = :id");
    $stmt->execute([':id' => $id_usuario]);
    if (!$stmt->fetch()) {
        echo "Usuário não encontrado.";
        exit;
    }

    $stmt = $conexao->prepare("SELECT id FROM obras WHERE id = :id");
    $stmt->execute([':id' => $id_obra]);
    if (!$stmt->fetch()) {
        echo "Obra não encontrada.";
        exit;
    }

    $conexao->beginTransaction();
    $sql = "INSERT INTO avaliacoes (id_usuario, id_obra, nota, comentario) VALUES (:id_usuario, :id_obra, :nota, :comentario)";
    $stmt = $conexao->prepare($sql);
    $stmt->execute([
        ':id_usuario' => $id_usuario,
        ':id_obra' => $id_obra,
        ':nota' => $nota,
        ':comentario' => $comentario
    ]);
    $conexao->commit();

    echo "<p>Avaliação cadastrada com sucesso!</p>";
    echo "<p><a href=\"consulta_avaliacoes.php\">Ver avaliações</a></p>";
} catch (PDOException $e) {
    if ($conexao->inTransaction())
        $conexao->rollBack();
    echo "Erro: " . htmlspecialchars($e->getMessage());
}
?>