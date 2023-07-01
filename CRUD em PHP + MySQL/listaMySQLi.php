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
    echo '<th>preco (R$)</th>';
    echo '</tr>';

    while ($produto = mysqli_fetch_assoc($produtos)) {
        echo '<tr>';
        echo '<td>' . $produto['codigo_prd'] . '</td>';
        echo '<td>' . $produto['descricao_prd'] . '</td>';
        echo '<td>' . $produto['data_cadastro'] . '</td>';
        echo '<td>' . $produto['preco'] . '</td>';
        echo '</tr>';
    }

    echo '<tfoot><tr><td colspan="5">Data atual: ' . retornarDataAtual() . '</td></tr>';
    echo '</table>';

    mysqli_free_result($produtos);
    mysqli_close($conn);
}

function listarDadosMySQLi_OO($filtro='%%') {
    $conn = conectarMySQLi_OO();

    $stmt = $conn->prepare('SELECT * FROM produto WHERE descricao_prd LIKE ?');
    $stmt->bind_param('s', $filtro);
    $stmt->execute();

    echo '<table class="mysqli">
              <caption>Relação de Produtos</caption>
              <tr>
                  <th>codigo_prd</th>
                  <th style="width: 40%;">descricao_prd</th>
                  <th >data_cadastro</th>
                  <th >preco (R$)</th>
              </tr>';

    $produtos = $stmt->get_result();
    while($produto = $produtos->fetch_assoc()) {
        $data_cadastro = date('d-m-Y', strtotime($produto['data_cadastro']));
        $preco = number_format($produto['peco'],2,',','.');

        echo "<tr>
                  <td>{$produto['codigo_prd']}</td>
                  <td>{$produto['descricao_prd']}</td>
                  <td style='text-align: center;'>{$data_cadastro}</td>
                  <td style='text-align: right;'>{$preco}</td>
              </tr>";
    }

    echo '<tfoot><tr><td colspan="5" style="text-align: center">Data atual: ' . retornarDataAtual() . '</td></tr>';
    echo '</table>';

    $produtos->free_result();
    $conn->close();
}