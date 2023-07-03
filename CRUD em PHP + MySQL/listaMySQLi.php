<?php
require_once 'conexoes.php';
require_once 'utils.php';

function listarDadosMySQLi_PD() {
    $conn = conectarMySQLi_PD();
    $produtos = mysqli_query($conn, 'SELECT * FROM produto');

    echo '<style>#PD th, #PD td {border: 1px solid}</style>';
    echo '<table id="PD" style="border-collapse: collapse; border: 2px solid">';
    echo '<caption>Relação de Produtos</caption>';
    echo '<tr>';
    echo '<th>codigo_prd</th>';
    echo '<th>descricao_prd</th>';
    echo '<th>data_cadastro</th>';
    echo '<th>preco</th>';
    echo '</tr>';

    while ($produto = mysqli_fetch_assoc($produtos)) {
        echo '<tr>';
        echo '<td>' . $produto['codigo_prd'] . '</td>';
        echo '<td>' . $produto['descricao_prd'] . '</td>';
        echo '<td>' . $produto['data_cadastro'] . '</td>';
        echo '<td>' . $produto['preco'] . '</td>';
        echo '</tr>';
    }

    echo '<tfoot><tr><td colspan="4">Data atual: ' . retornarDataAtual() . '</td></tr></tfoot>';
    echo '</table>';

    mysqli_free_result($produtos);
    mysqli_close($conn);
}


function listarDadosMySQLi_OO($filtro = '%%') {
    $conn = conectarMySQLi_OO();

    $stmt = $conn->prepare('SELECT * FROM produto WHERE descricao_prd LIKE ?');
    $stmt->bind_param('s', $filtro);
    $stmt->execute();

    echo '<table class="mysqli">
              <caption>Relação de Produtos</caption>
              <tr>
                  <th>codigo_prd</th>
                  <th style="width: 40%;">descricao_prd</th>
                  <th>data_cadastro</th>
                  <th>preco (R$)</th>
              </tr>';

    $produtos = $stmt->get_result();
    while ($produto = $produtos->fetch_assoc()) {
        $data_cadastro = date('d-m-Y', strtotime($produto['data_cadastro']));
        $preco = number_format($produto['preco'], 2, ',', '.');

        echo "<tr>
                  <td>{$produto['codigo_prd']}</td>
                  <td>{$produto['descricao_prd']}</td>
                  <td>{$data_cadastro}</td>
                  <td>{$preco}</td>
              </tr>";
    }

    echo '<tfoot><tr><td colspan="4" style="text-align: center">Data atual: ' . retornarDataAtual() . '</td></tr></tfoot>';
    echo '</table>';

    $produtos->free_result();
    $conn->close();
}

require_once 'dados_acesso.php';
require_once 'utils.php';
mysqli_report(MYSQLI_REPORT_OFF);
function conectarPDO()
{
    try {
        $conn = new PDO(DSN . ':host=' . SERVIDOR . ';crud_produtos=' . BANCODEDADOS, USUARIO, SENHA);
        console_log('Conexão com PDO realizada com sucesso!');
        return $conn;
    } catch (PDOException $e) {
//        echo '<h3>Erro: ' . mb_convert_encoding($e->getMessage(), 'UTF-8', 'ISO-8859-1') . '</h3>';
        echo '<h3>Erro: ' . $e->getMessage() . '</h3>';
        exit();
    }
}

function conectarMySQLi_PD()
{
    $conn = @mysqli_connect(SERVIDOR, USUARIO, SENHA, BANCODEDADOS);
    if (!$conn) {
//        die('<h3>Erro: ' . mb_convert_encoding(mysqli_connect_error(), 'UTF-8', 'ISO-8859-1') . '</h3>');
        die('<h3>Erro: ' . mysqli_connect_error() . '</h3>');
    } else {
        console_log('Conexão com MySQLi Procedural realizada com sucesso!');
    }
    return $conn;
}

function conectarMySQLi_OO()
{
    $conn = @new mysqli(SERVIDOR, USUARIO, SENHA, BANCODEDADOS);
    if ($conn->connect_error) {
//        echo '<h3>Erro: ' . mb_convert_encoding($conn->connect_error, 'UTF-8', 'ISO-8859-1') . '</h3>';
        echo '<h3>Erro: ' . $conn->connect_error . '</h3>';
        exit();
    } else {
        console_log('Conexão com MySQLi Orientado a Objetos realizada com sucesso!');
    }
    return $conn;
}