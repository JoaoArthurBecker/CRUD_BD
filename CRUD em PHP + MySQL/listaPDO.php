<?php
require_once "conexoes.php";
require_once 'utils.php';

function listarDadosPDO($filtro='%%') {
    $conn = conectarPDO();

    $stmt = $conn->prepare('SELECT * FROM aluno WHERE nome LIKE :nome_aluno');
    $stmt->bindParam(':nome_aluno', $filtro, PDO::PARAM_STR);
    $stmt->execute();

    echo '<div class="container table-responsive">';
    echo '<table class="table table-striped table-bordered table-hover">
              <caption>Relação de Alunos</caption>
              <thead class="table-dark">
                  <tr>
                      <th>Id</th>
                      <th>Nome</th>
                      <th>Nascimento</th>
                      <th>Salário (R$)</th>
                  </tr>
              </thead>';

    while($aluno = $stmt->fetch()) {
        $data_nascimento = date('d-m-Y', strtotime($aluno['nascimento']));
        $salario = number_format($aluno['salario'],2,',','.');

        echo "<tr>
                  <td style='width: 10%;'>{$aluno['id_aluno']}</td>
                  <td style='width: 40%;'>{$aluno['nome']}</td>
                  <td style='width: 25%;' class='text-center'>{$data_nascimento}</td>
                  <td style='width: 25%;' class='text-end'>{$salario}</td>
              </tr>";
    }

    echo '<tfoot><tr><td colspan="5" style="text-align: center">Data atual: ' . retornarDataAtual() . '</td></tr>';
    echo '</table></div>';

    // Fecha consulta e conexão, liberando recursos
    $stmt = null;
    $conn = null;
}