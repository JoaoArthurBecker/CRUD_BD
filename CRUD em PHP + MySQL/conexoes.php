<?php
require_once 'dados_acesso.php';
require_once 'utils.php';
mysqli_report(MYSQLI_REPORT_OFF);
function verificaBD($conn) {
    // Verifica no dicionário de dados do SGBD se o banco de dados existe
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "' . BANCODEDADOS . '"');
    if (!$stmt->fetchColumn()) {
        // Cria o banco se ele não existir
        $stmt = $conn->query('CREATE DATABASE IF NOT EXISTS ' . BANCODEDADOS);
    }
}

function verificaTabelaCategoria($conn) {
    // Verifica se a tabela produto existe
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES 
                          WHERE (TABLE_SCHEMA = "'.BANCODEDADOS.'") AND (TABLE_NAME = "categoria")');

    if (!$stmt->fetchColumn()) {
        // Cria a tabela 'categoria' se ela não existir e a popula com alguns registros
        $stmt = $conn->query('CREATE TABLE categoria (
                                codigo_ctg INT AUTO_INCREMENT PRIMARY KEY,
                                descricao_ctg VARCHAR(50) UNIQUE NOT NULL
                            ) ENGINE=InnoDB;');

        $stmt = $conn->query('INSERT INTO categoria (
                                VALUES (null, "Alimento"),
                                       (null, "Higiene pessoal"),
                                       (null, "Bebida"),
                                       (null, "Higiene domestica")
                             );');
    }
}
function verificaTabelaProduto($conn) {
    // Verifica se a tabela produto existe
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES 
                          WHERE (TABLE_SCHEMA = "'.BANCODEDADOS.'") AND (TABLE_NAME = "produto")');

    if (!$stmt->fetchColumn()) {
        // Cria a tabela 'aluno' se ela não existir e a popula com alguns registros
        $stmt = $conn->query('CREATE TABLE IF NOT EXISTS produto (
                                codigo_prd INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
                                descricao_prd VARCHAR(50) UNIQUE NOT NULL,
                                data_cadastro DATE NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                preco DECIMAL(10,2) NOT NULL DEFAULT 0.0,
                                ativo BOOL NOT NULL DEFAULT true,
                                unidade CHAR(5) DEFAULT \'un\',
                                tipo_comissao ENUM(\'s\', \'f\', \'p\') NOT NULL DEFAULT \'s\',
                                codigo_ctg INT NOT NULL,
                                foto LONGBLOB,
                                FOREIGN KEY (codigo_ctg) REFERENCES categoria(codigo_ctg)
                            ) ENGINE=InnoDB;');


        $foto = file_get_contents('default.png');

        $stmt = $conn->prepare('INSERT INTO produto 
                                VALUES (null, "Esponja", "2010-10-25", 4.99, true, 100, "s", 2, :foto),
                                       (null, "Coxinha", "2013-04-20", 4.50, true, 10, "s", 1, :foto);');
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

        verificaTabelaProduto($conn);
        verificaTabelaCategoria($conn);

        return $conn;
    } catch (PDOException $e) {
//        echo '<h3>Erro: ' . mb_convert_encoding($e->getMessage(), 'UTF-8', 'ISO-8859-1') . '</h3>';
        echo '<h3>Erro: ' . $e->getMessage() . '</h3>';

        exit();
    }
}