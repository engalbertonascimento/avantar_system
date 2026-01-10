<?php
// actions/listar_contatos.php
require_once 'config.php';
header('Content-Type: application/json');

// Erros de PHP podem quebrar o JSON, desativamos a exibição direta se houver erro
error_reporting(0); 

$sql = "SELECT * FROM contatos ORDER BY id DESC";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(["status" => "error", "message" => $conn->error]);
    exit;
}

$contatos = [];
while($row = $result->fetch_assoc()) {
    $contatos[] = $row;
}

echo json_encode($contatos);
$conn->close();
?>