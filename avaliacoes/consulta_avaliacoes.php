<?php
require_once __DIR__ . '/../bd/conexaoBD.php';

$sql = "
SELECT a.id, a.nota, a.comentario, a.criado_em,
       u.id AS usuario_id, u.email AS usuario_email,
       o.id AS obra_id, o.nome AS obra_nome, o.url_poster
FROM avaliacoes a
JOIN usuario u ON a.id_usuario = u.id
JOIN obras o ON a.id_obra = o.id
ORDER BY a.criado_em DESC
";
$stmt = $conexao->query($sql);
$avaliacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Consulta de Avaliações</title>
<style>table, th, td {border:1px solid #ccc; border-collapse:collapse; padding:6px;}</style>
</head>
<body>
<main>
  <h1>Avaliações</h1>
  <a href="../index.html">Voltar</a>
  <table>
    <thead><tr><th>Usuário</th><th>Obra</th><th>Pôster</th><th>Nota</th><th>Comentário</th><th>Data</th></tr></thead>
    <tbody>
      <?php if (empty($avaliacoes)): ?>
        <tr><td colspan="6">Nenhuma avaliação encontrada.</td></tr>
      <?php else: ?>
        <?php foreach ($avaliacoes as $a): ?>
          <tr>
            <td><?= htmlspecialchars($a['usuario_email']) ?></td>
            <td><?= htmlspecialchars($a['obra_nome']) ?></td>
            <td><?php if ($a['url_poster']): ?><img src="<?= htmlspecialchars($a['url_poster']) ?>" alt="" style="max-width:80px;max-height:110px"><?php endif;?></td>
            <td><?= htmlspecialchars($a['nota']) ?></td>
            <td><?= nl2br(htmlspecialchars($a['comentario'])) ?></td>
            <td><?= htmlspecialchars($a['criado_em']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</main>
</body>
</html>
