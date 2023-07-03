<?php
require_once "conexoes.php";
require_once 'utils.php';

function listarDadosPDO($filtro='%%') {
    $conn = conectarPDO();

    $stmt = $conn->prepare('SELECT * FROM produto WHERE descricao_prd LIKE :descricao_prd');
    $stmt->bindParam(':descricao_prd', $filtro, PDO::PARAM_STR);
    $stmt->execute();

    echo '<div class="container table-responsive">';
    echo '<table class="table table-striped table-bordered table-hover">
              <caption>Relação de Produtos</caption>
              <thead class="table-dark">
                  <tr> 
                      <th >codigo_prd</th>
                      <th >descricao_prd</th>
                      <th >data_cadastro</th>
                      <th >preco (R$)</th>
                  </tr>
              </thead>';

    while($produto = $stmt->fetch()) {
        $data_cadastro = date('d-m-Y', strtotime($produto['nascimento']));
        $preco = number_format($produto['preco'],2,',','.');

        echo "<tr>
                  <td style='width: 10%;'>{$produto['codigo_prd']}</td>
                  <td style='width: 40%;'>{$produto['descricao_prd']}</td>
                  <td style='width: 25%;' class='text-center'>{$data_cadastro}</td>
                  <td style='width: 25%;' class='text-end'>{$preco}</td>
              </tr>";
    }

    echo '<tfoot><tr><td colspan="5" style="text-align: center">Data atual: ' . retornarDataAtual() . '</td></tr>';
    echo '</table></div>';

    // Fecha consulta e conexão, liberando recursos
    $stmt = null;
    $conn = null;
}