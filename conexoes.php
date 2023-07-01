<?php
require_once 'dados_acesso.php';
require_once 'utils.php';
mysqli_report(MYSQLI_REPORT_OFF);
function verificaBD($conn) {
    // Verifica no dicionário de dados do SGBD se o banco de dados existe
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' . CRUD_PRODUTOS . '"');
    if (!$stmt->fetchColumn()) {
        // Cria o banco se ele não existir
        $stmt = $conn->query('CREATE DATABASE IF NOT EXISTS ' . CRUD_PRODUTOS);
    }
}

function verificaTabelaCat($conn) {
    // Verifica se a tabela categorias existe
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES 
                          WHERE (TABLE_SCHEMA = "'.CRUD_PRODUTOS.'") AND (TABLE_NAME = "categorias")');

    if (!$stmt->fetchColumn()) {
        // Cria a tabela  categorias se ela não existir e a popula com alguns registros
        $stmt = $conn->query('CREATE TABLE IF NOT EXISTS categorias ( 
                                id_cat int AUTO_INCREMENT NOT NULL PRIMARY KEY,
                                                               descricao varchar (50) NOT NULL UNIQUE
                              ) ENGINE=InnoDB;');

        $stmt = $conn->query('INSERT INTO categorias
                              VALUES (null, "materiais elétricos"),
                                     (null, "materiais hidráulicos"),
                                     (null, "Acabamento"),
                                     (null, "Carpintaria "),
                                     (null, "Diverços");');
    }
}

function verificaTabelaArtigos($conn) {
    // Verifica se a tabela materiais existe
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES 
                          WHERE (TABLE_SCHEMA = "'.CRUD_PRODUTOS.'") AND (TABLE_NAME = "artigos")');

    if (!$stmt->fetchColumn()) {
        // Cria a tabela 'artigo' se ela não existir e a popula com alguns registros
        $stmt = $conn->query('CREATE TABLE IF NOT EXISTS aluno ( 
                                  id_aluno int NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                  descricao varchar(60) NOT NULL UNIQUE,
                                  cadastro date DEFAULT 'retornarDataAtual()',
                                  preco decimal(10,2 >0) DEFAULT '0,00',
                                  ativo tinyint(1) NOT NULL DEFAULT "1",
quantidade varchar (5) defoult "1",
                                  comicao enum( "s", "f", "p", ) NOT NULL  default "f",
                                  id_categoria int DEFAULT NULL,
                                  foto longblob,
                                  FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
                                ) ENGINE=InnoDB;');

        $foto = file_get_contents('default.png');
        $stmt = $conn->prepare('INSERT INTO artigos 
                                VALUES (null, "Fio de Cobre", "1990-10-25", 42.42, "n", 0, 1, :foto),
                                       (null, "Milheiro Tijolo", "2000-01-01", 840.56, "f", 1, 2, :foto);');
        $stmt->bindParam(':foto', $foto, PDO::PARAM_LOB);
        $stmt->execute();
    }
}

function conectarPDO()
{
    try {
        // Realiza a conexão com o SGBD sem informar o banco de dados
        $conn = new PDO(DSN . ':host=' . SERVIDOR,
            USUARIO,
            SENHA);
        console_log('Conexão com PDO realizada com sucesso!');

        verificaBD($conn);

        // Abre uma conexão com o banco de dados
        $conn = new PDO(DSN . ':host=' . SERVIDOR . ';dbname=' . BANCODEDADOS,
            USUARIO,
            SENHA);

        verificaTabelaCat($conn);
        verificaTabelaArtigos($conn);

        return $conn;
    } catch (PDOException $e) {
//        echo '<h3>Erro: ' . mb_convert_encoding($e->getMessage(), 'UTF-8', 'ISO-8859-1') . '</h3>';
        echo '<h3>Erro: ' . $e->getMessage() . '</h3>';

        exit();
    }
}