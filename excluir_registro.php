<?php
require_once "conexoes.php";

$id = $_REQUEST['id_aluno'];

if($id) {
    $conn = conectarPDO();

    $sql = 'DELETE FROM aluno WHERE id_aluno=:id_aluno';
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_aluno', $id, PDO::PARAM_STR);

    if ($stmt->execute()) {
        if ($stmt->rowCount()) {
            echo json_encode(array('statusCode' => 200));
        } else {
            echo json_encode(array('statusCode' => 201));
        }
    }
}
?>