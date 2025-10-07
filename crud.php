<?php
require_once "BD/conexaoBD.php";
try {
  $stmt = $conexao->query("SELECT * FROM obras ORDER BY nome");
  $obras = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  echo "Erro na consulta: " . $e->getMessage();
  $obras = [];
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>WatchPoint - CRUD</title>
  <link rel="stylesheet" href="css/style.css" />
  <link rel="stylesheet" href="css/crud.css" />
</head>

<body>
  <header id="navegador">
    <div id="nav-esquerda">
      <div id="logo">
        <img src="img-gerais/logo_watchpoint.png" alt="Logo WatchPoint" />
      </div>
      <div id="menu-wrapper">
        <input type="checkbox" id="menu-drop" class="menu-drop" />
        <label for="menu-drop" class="menu-texto">&#9776; Menu</label>
        <nav class="menu">
          <a href="index.html">Home</a>
          <a href="pag-gerais/animes.html">Animes</a>
          <a href="pag-gerais/series.html">Séries</a>
          <a href="pag-gerais/filmes.html">Filmes</a>
          <a href="historia.html">História</a>
        </nav>
      </div>
      <div class="separador"></div>
    </div>

    <div id="nav-centro">
      <form class="pesquisa">
        <label for="pesquisa-inline" style="display: none">Pesquisar</label>
        <input type="text" id="pesquisa-inline" placeholder="🔍︎ Buscar título..." />
      </form>
    </div>

    <div id="nav-direita">
      <nav class="separador"></nav>
      <nav class="nav">
        <a href="crud.php">Crud</a>
        <a href="biblioteca.html">Biblioteca</a>
        <a href="login.html">Login</a>
      </nav>
      <nav class="separador" style="margin-left: -50px; background-color: transparent"></nav>
    </div>
  </header>

  <h1 id="titulo_principal">CRUD de Obras</h1>

  <main>
    <div id="janela-add">
      <form class="form" id="form-cadastro" action="crud_obras/cadastrar_obra.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" placeholder="Digite o nome" required />

        <label for="tipo">Tipo:</label>
        <select id="tipo" name="tipo" required>
          <option value="" disabled selected hidden>Escolha um Tipo</option>
          <option value="Anime">Anime</option>
          <option value="Filme">Filme</option>
          <option value="Série">Série</option>
        </select>

        <label for="poster">URL do Pôster:</label>
        <input type="url" id="poster" name="url_poster" placeholder="https://..." required />

        <label for="sinopse">Sinopse:</label>
        <textarea id="sinopse" name="sinopse" rows="3" placeholder="Descreva brevemente" required></textarea>

        <label for="episodios">Episódios:</label>
        <input type="text" id="episodios" name="episodios" placeholder="Ex: 24 ou -" />

        <label for="nota">Nota (0 a 10):</label>
        <input type="number" id="nota" name="nota" placeholder="Ex: 8.4" step="0.1" />

        <label for="generos">Gêneros (separe por vírgula):</label>
        <input type="text" id="generos" name="generos" placeholder="Ação, Aventura" />

        <button id="btn-cadastrar" type="submit">Salvar Obra</button>
      </form>
    </div>

    <table>
      <thead>
        <tr>
          <th scope="col">Nome</th>
          <th scope="col">Tipo</th>
          <th scope="col">Pôster</th>
          <th scope="col">Sinopse</th>
          <th scope="col">Nota</th>
          <th scope="col">Gêneros</th>
          <th scope="col">Ações</th>
        </tr>
      </thead>
      <tbody id="obrasTabela">
        <?php foreach ($obras as $obra): ?>
          <tr>
            <td><?= htmlspecialchars($obra['nome']) ?></td>
            <td><?= htmlspecialchars($obra['tipo']) ?></td>
            <td><img class="poster" src="<?= htmlspecialchars($obra['url_poster']) ?>"
                alt="<?= htmlspecialchars($obra['nome']) ?>" /></td>
            <td data-label="Sinopse"><p class="sinopse"><?= htmlspecialchars($obra['sinopse']) ?></p></td>
            <td><?= htmlspecialchars($obra['nota']) ?></td>
            <td>
              <ul class="generos" style="text-align: left; padding-left: 10px;">
                <?php
                $generosArray = explode(',', $obra['generos']);
                foreach ($generosArray as $genero) {
                  echo '<li>' . htmlspecialchars(trim($genero)) . '</li>';
                }
                ?>
              </ul>
            </td>
            <td class="acoes">
              <div class="botoes">
                <a href="crud_obras/editar_obra.php?id=<?= $obra['id'] ?>" class="botao-editar">Editar</a>
                <a href="crud_obras/excluir_obra.php?id=<?= $obra['id'] ?>" class="botao-remover"
                  onclick="return confirm('Tem certeza?');">Remover</a>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </main>

</body>

</html>