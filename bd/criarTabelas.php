<?php
require_once "conexaoBD.php";

try {
    $conexao->exec("SET foreign_key_checks = 0");
    $conexao->exec("DROP TABLE IF EXISTS usuario, obras, generos, obras_generos");
    $conexao->exec("SET foreign_key_checks = 1");

    $sql_usuario = "
        CREATE TABLE IF NOT EXISTS usuario (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            data_nascimento DATE NOT NULL
        )
    ";
    $conexao->exec($sql_usuario);
    echo "Tabela 'usuario' criada com sucesso.<br>";

    $sql_obras = "
        CREATE TABLE IF NOT EXISTS obras (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            tipo ENUM('Filme', 'Série', 'Anime') NOT NULL,
            url_poster TEXT,
            sinopse TEXT,
            duracao_ou_episodios VARCHAR(25) NOT NULL,
            nota DECIMAL(3, 1)
        );
    ";
    $conexao->exec($sql_obras);
    echo "Tabela 'obras' criada com sucesso.<br>";

    $sql_generos = "
        CREATE TABLE IF NOT EXISTS generos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL UNIQUE
        )
    ";
    $conexao->exec($sql_generos);
    echo "Tabela 'generos' criada com sucesso.<br>";

    $conexao->exec("INSERT INTO generos (nome) VALUES 
        ('Ação'), ('Animação'), ('Artes Marciais'), ('Aventura'), ('Biografia'), 
        ('Comédia'), ('Comédia Dramática'), ('Comédia Romântica'), ('Crime'), 
        ('Cyberpunk'), ('Documentário'), ('Drama'), ('Esporte'), ('Família'), 
        ('Fantasia'), ('Faroeste'), ('Ficção Científica'), ('Game Show'), ('Guerra'), 
        ('Histórico'), ('Infantil'), ('Isekai'), ('Josei'), ('Mahou Shoujo'), 
        ('Mecha'), ('Mistério'), ('Musical'), ('Noir'), ('Paródia'), ('Policial'), 
        ('Pós-apocalíptico'), ('Reality Show'), ('Romance'), ('Sátira'), ('Seinen'), 
        ('Shojo'), ('Shonen'), ('Slice of Life'), ('Space Opera'), ('Steampunk'), 
        ('Super-herói'), ('Suspense'), ('Talk Show'), ('Terror'), ('Thriller'), 
        ('Thriller Psicológico'), ('Western')
        ON DUPLICATE KEY UPDATE nome=nome;");

    $sql_obras_generos = "
        CREATE TABLE IF NOT EXISTS obras_generos (
            id_obra INT NOT NULL,
            id_genero INT NOT NULL,
            PRIMARY KEY (id_obra, id_genero),
            FOREIGN KEY (id_obra) REFERENCES obras(id) ON DELETE CASCADE,
            FOREIGN KEY (id_genero) REFERENCES generos(id) ON DELETE CASCADE
        )
    ";
    $conexao->exec($sql_obras_generos);
    echo "Tabela 'obras_generos' criada com sucesso.<br>";

    $conexao->exec("CREATE INDEX idx_obras_nome ON obras(nome);");

    $conexao->exec("CREATE INDEX idx_obras_tipo ON obras(tipo);");

    $sql_avaliacoes = "
        CREATE TABLE IF NOT EXISTS avaliacoes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_usuario INT NOT NULL,
            id_obra INT NOT NULL,
            nota DECIMAL(3,1) NOT NULL,
            comentario TEXT,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (id_usuario) REFERENCES usuario(id) ON DELETE CASCADE,
            FOREIGN KEY (id_obra) REFERENCES obras(id) ON DELETE CASCADE
        )
    ";
    $conexao->exec($sql_avaliacoes);
    echo "Tabela 'avaliacoes' criada com sucesso.<br>";

    $conexao->exec("CREATE INDEX idx_avaliacoes_usuario ON avaliacoes(id_usuario);");
    $conexao->exec("CREATE INDEX idx_avaliacoes_obra ON avaliacoes(id_obra);");
} catch (PDOException $e) {
    echo "Erro ao criar as tabelas ou índices: " . $e->getMessage();
}
?>