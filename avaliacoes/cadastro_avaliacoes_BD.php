<?php
require_once __DIR__ . '/../bd/conexaoBD.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;
    $id_obra = isset($_POST['id_obra']) ? intval($_POST['id_obra']) : 0;
    $nota = isset($_POST['nota']) ? floatval($_POST['nota']) : null;
    $comentario = $_POST['comentario'] ?? '';

    if ($id_usuario <= 0 || $id_obra <= 0 || $nota === null) {
        $erro = "Por favor, preencha todos os campos obrigatórios.";
    } else {
        try {
            $stmt = $conexao->prepare("SELECT id FROM usuario WHERE id = :id");
            $stmt->execute([':id' => $id_usuario]);
            if (!$stmt->fetch()) {
                $erro = "Usuário não encontrado.";
            } else {
                $stmt = $conexao->prepare("SELECT id FROM obras WHERE id = :id");
                $stmt->execute([':id' => $id_obra]);
                if (!$stmt->fetch()) {
                    $erro = "Obra não encontrada.";
                } else {
                    $conexao->beginTransaction();
                    $sql = "INSERT INTO avaliacoes (id_usuario, id_obra, nota, comentario) 
                            VALUES (:id_usuario, :id_obra, :nota, :comentario)";
                    $stmt = $conexao->prepare($sql);
                    $stmt->execute([
                        ':id_usuario' => $id_usuario,
                        ':id_obra' => $id_obra,
                        ':nota' => $nota,
                        ':comentario' => $comentario
                    ]);
                    $conexao->commit();

                    $sucesso = true;
                }
            }
        } catch (PDOException $e) {
            if ($conexao->inTransaction()) {
                $conexao->rollBack();
            }
            $erro = "Erro ao cadastrar avaliação: " . $e->getMessage();
        }
    }
}

$usuarios = $conexao->query("SELECT id, email FROM usuario ORDER BY email")->fetchAll(PDO::FETCH_ASSOC);

$obras = $conexao->query("SELECT id, nome, tipo FROM obras ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastrar Avaliação</title>
</head>

<body>
    <main>
        <h1>Cadastrar Nova Avaliação</h1>

        <?php if (isset($sucesso) && $sucesso): ?>
            <div class="mensagem-sucesso">
                <p>Avaliação cadastrada com sucesso!</p>
                <p><a href="consulta_avaliacoes.php">Ver lista de avaliações</a></p>
            </div>
        <?php endif; ?>

        <?php if (isset($erro)): ?>
            <div class="mensagem-erro">
                <p><?= htmlspecialchars($erro) ?></p>
            </div>
        <?php endif; ?>

        <?php if (empty($usuarios)): ?>
            <div class="mensagem-erro">
                <p>Nenhum usuário cadastrado. <a href="../usuario/cadastro_usuario.html">Cadastre um usuário primeiro</a>.
                </p>
            </div>
        <?php elseif (empty($obras)): ?>
            <div class="mensagem-erro">
                <p>Nenhuma obra cadastrada. <a href="../obras/cadastro_obras.php">Cadastre uma obra primeiro</a>.</p>
            </div>
        <?php else: ?>
            <form method="POST">
                <label for="id_usuario">Usuário: *</label>
                <select id="id_usuario" name="id_usuario" required>
                    <option value="">-- Selecione um usuário --</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?= $usuario['id'] ?>" <?= (isset($_POST['id_usuario']) && $_POST['id_usuario'] == $usuario['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($usuario['email']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="id_obra">Obra: *</label>
                <select id="id_obra" name="id_obra" required>
                    <option value="">-- Selecione uma obra --</option>
                    <?php foreach ($obras as $obra): ?>
                        <option value="<?= $obra['id'] ?>" <?= (isset($_POST['id_obra']) && $_POST['id_obra'] == $obra['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($obra['nome']) ?> (<?= htmlspecialchars($obra['tipo']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="nota">Nota (0 a 10): *</label>
                <input type="number" id="nota" name="nota" step="0.1" min="0" max="10" required
                    value="<?= isset($_POST['nota']) ? htmlspecialchars($_POST['nota']) : '' ?>" placeholder="Ex: 8.5">

                <label for="comentario">Comentário:</label>
                <textarea id="comentario" name="comentario" rows="5"
                    placeholder="Escreva sua opinião sobre a obra..."><?= isset($_POST['comentario']) ? htmlspecialchars($_POST['comentario']) : '' ?></textarea>

                <button type="submit">Cadastrar Avaliação</button>
            </form>
        <?php endif; ?>

        <br>
        <a href="../index.html">Voltar para a página inicial</a>
    </main>
</body>

</html>