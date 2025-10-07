<?php
require_once "conexaoBD.php";

try {
    $sql_usuario = "
        CREATE TABLE IF NOT EXISTS usuario (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            data_nascimento DATE NOT NULL
        )
    ";
    $conexao->exec($sql_usuario);
    echo "Tabela 'usuario' criada com sucesso (ou já existia).<br>";

    $sql_obras = "
        CREATE TABLE IF NOT EXISTS obras (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(255) NOT NULL,
            tipo ENUM('Filme', 'Série', 'Anime') NOT NULL,
            url_poster TEXT,
            sinopse TEXT,
            duracao_ou_episodios VARCHAR(25) NOT NULL,
            nota DECIMAL(3, 1),
            generos VARCHAR(255)
        );
    ";
    $conexao->exec($sql_obras);
    echo "Tabela 'obras' criada com sucesso (ou já existia).<br>";

} catch (PDOException $e) {
    echo "Erro ao criar as tabelas: " . $e->getMessage();
}
?>