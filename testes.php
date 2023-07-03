<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Testes Diversos</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
require_once 'listaPDO.php';
require_once 'conexoes.php';

verificaBD();
echo '<hr>';

verificaTabelaCategoria();
echo '<hr>';

verificaTabelaProduto();
echo '<hr>';

// Listando os dados em forma de tabela
listarDadosMySQLi_PD();
echo '<hr>';

listarDadosMySQLi_OO('bel%');
echo '<hr>';

listarDadosPDO();
?>
</body>
</html>