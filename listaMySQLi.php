<?php
require_once 'conexoes.php';
require_once 'utils.php';

function listarDadosMySQLi_PD() {
    $conn = conectarMySQLi_PD()
    $alunos = mysqli_query($conn, 'SELECT * FROM produto');

    echo '<style>#PD th, #PD td {border: 1px solid}</style>';
    echo '<table id="PD" style="border-collapse: collapse; border: 2px solid">';
    echo '<caption>Relação de Alunos</caption>';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Nome</th>';
    echo '<th>Nascimento</th>';
    echo '<th>Salário (R$)</th>';
    echo '</tr>';

    while ($produto = mysqli_fetch_assoc($produtos)) {
        echo '<tr>';
        echo '<td>' . $produto['id_aluno'] . '</td>';
        echo '<td>' . $produto['nome'] . '</td>';
        echo '<td>' . $produto['nascimento'] . '</td>';
        echo '<td>' . $produto['salario'] . '</td>';
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
              <caption>Relação de Alunos</caption>
              <tr>
                  <th>ID</th>
                  <th style="width: 40%;">Nome</th>
                  <th >Nascimento</th>
                  <th >Salário (R$)</th>
              </tr>';

    $alunos = $stmt->get_result();
    while($aluno = $alunos->fetch_assoc()) {
        $data_nascimento = date('d-m-Y', strtotime($aluno['nascimento']));
        $salario = number_format($aluno['salario'],2,',','.');

        echo "<tr>
                  <td>{$aluno['id_aluno']}</td>
                  <td>{$aluno['nome']}</td>
                  <td style='text-align: center;'>{$data_nascimento}</td>
                  <td style='text-align: right;'>{$salario}</td>
              </tr>";
    }

    echo '<tfoot><tr><td colspan="5" style="text-align: center">Data atual: ' . retornarDataAtual() . '</td></tr>';
    echo '</table>';

    $alunos->free_result();
    $conn->close();
}