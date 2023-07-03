<?php
require_once "conexoes.php";

$id = $_REQUEST['codigo prd'];

if($id) {
    $conn = conectarPDO();

    $sql = 'DELETE FROM produto WHERE codigo_prd=:codigo_prd';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':codigo_prd', $codigo_prd, PDO::PARAM_STR);

    if ($stmt->execute()) {
        if ($stmt->rowCount()) {
            echo json_encode(array('statusCode' => 200));
        } else {
            echo json_encode(array('statusCode' => 201));
        }
    }
}
?>