<?php
require_once 'dados_acesso.php';
require_once 'utils.php';
mysqli_report(MYSQLI_REPORT_OFF);

function verificaBD($conn)
{
    // Verifica no dicionário de dados do SGBD se o banco de dados existe
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = " '. BANCODEDADOS .' "');
    if (!$stmt->fetchColumn()) {
        // Cria o banco se ele não existir
        $stmt = $conn->query('CREATE DATABASE IF NOT EXISTS ' . BANCODEDADOS);
    }
}

function verificaTabelaCategoria($conn)
{
    // Verifica se a tabela produto existe
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES 
                          WHERE (TABLE_SCHEMA = "' . BANCODEDADOS . '") AND (TABLE_NAME = "categoria")');

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

function verificaTabelaProduto($conn)
{
    // Verifica se a tabela produto existe
    $stmt = $conn->query('SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES 
                          WHERE (TABLE_SCHEMA = "' . BANCODEDADOS . '") AND (TABLE_NAME = "produto")');

    if (!$stmt->fetchColumn()) {
        // Cria a tabela 'produto' se ela não existir e a popula com alguns registros
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
                                VALUES (null, "Esponja", "2010-10-25", 4.99, true, 100, "s", 2, ?),
                                       (null, "Coxinha", "2013-04-20", 4.50, true, 10, "s", 1, ?);');
        $stmt->bind_param('s', $foto);

        $stmt->send_long_data(0, $foto);
        $stmt->send_long_data(1, $foto);
        $stmt->execute();
    }
}

