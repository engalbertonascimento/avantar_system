<?php
require_once 'config.php';
header('Content-Type: application/json');

$id      = $_POST['id'];
$status  = $_POST['status_contato'];
$valor   = $_POST['valor_cotacao'] ?: 0;
$nova_obs = $_POST['observacoes'];
$data_retorno = $_POST['data_retorno_prevista'] ?: null;

// 1. Primeiro, buscamos a observação antiga para não apagar o histórico
$res = $conn->query("SELECT observacoes FROM contatos WHERE id = $id");
$contato = $res->fetch_assoc();
$obs_antiga = $contato['observacoes'];

// 2. Montamos o novo texto com Data e Hora (Timeline)
if (!empty($nova_obs)) {
    $data_hora = date('d/m/Y H:i');
    $obs_acumulada = "[$data_hora]: $nova_obs\n" . $obs_antiga;
} else {
    $obs_acumulada = $obs_antiga;
}

// 3. Atualizamos tudo, incluindo a nova data de retorno
$sql = "UPDATE contatos SET status_contato = ?, valor_cotacao = ?, observacoes = ?, data_retorno_prevista = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sdssi", $status, $valor, $obs_acumulada, $data_retorno, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>