<?php
require_once "conexaoBD.php";

try {
    $sql_usuarios = "
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(100) NOT NULL UNIQUE,
            senha VARCHAR(255) NOT NULL,
            data_nascimento DATE
        )
    ";
    $conexao->exec($sql_usuarios);
    echo "Tabela 'usuarios' criada com sucesso (ou já existia).<br>";

    $sql_obras = "
        CREATE TABLE IF NOT EXISTS obras (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nome VARCHAR(100) NOT NULL,
            tipo VARCHAR(10) NOT NULL,
            url_poster VARCHAR(255) NOT NULL,
            sinopse TEXT NOT NULL,
            episodios VARCHAR(20),
            nota DECIMAL(3,1),
            generos TEXT
        );
    ";
    $conexao->exec($sql_obras);
    echo "Tabela 'obras' criada com sucesso (ou já existia).<br>";

} catch (PDOException $e) {
    echo "Erro ao criar as tabelas: " . $e->getMessage();
}
?>