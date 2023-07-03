<?php
$stmt = $conn->prepare('SELECT codigo_prd, descricao_prd, data_cadastro, preco, c.codigo_ctg, descricao_ctg AS categoria, foto
                        FROM produto p
                        JOIN categoria c ON c.codigo_ctg = p.codigo_ctg 
                        WHERE codigo_prd = :codigo_prd ');

$stmt->bindParam(':codigo_prd', $codigo_prd, PDO::PARAM_INT);
$stmt->execute();
$produto = $stmt->fetch();

if ($produto) {
    $codigo_prd = $produto['codigo_prd'];
    $descricao_prd = $produto['descricao_prd'];
    $data_cadastro = $produto['data_cadastro'];
    $preco = $produto['preco'];
    $descricao_ctg = $produto['categoria'];
    $foto = $produto['foto'];
?>

<ul>
    <li><b>Codigo: </b><?= $codigo_prd ?></li>
    <li><b>Produto: </b><?= $descricao_prd ?></li>
    <li><b>Cadastro: </b><?= $data_cadastro ?></li>
    <li><b>Preco: </b><?= $preco ?></li>
    <li><b>Categoria: </b><?= $descricao_ctg ?></li>
</ul>

<?php
} else {
    echo "Produto não encontrado.";
}