<?php
// actions/atualizar_status.php
require_once 'config.php';
header('Content-Type: application/json');

$id     = $_POST['id'] ?? null;
$status = $_POST['status'] ?? null;

if (!$id || !$status) {
    echo json_encode(["status" => "error", "message" => "Dados incompletos."]);
    exit;
}

// 1. Atualiza o status principal do cliente
// Se o status for "Contato sem êxito", podemos automaticamente colocar uma data de retorno para amanhã
$data_retorno = ($status == 'Contato sem êxito') ? date('Y-m-d', strtotime('+1 day')) : null;

if ($data_retorno) {
    $sql = "UPDATE contatos SET status_contato = ?, data_retorno = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $status, $data_retorno, $id);
} else {
    $sql = "UPDATE contatos SET status_contato = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
}

if ($stmt->execute()) {
    // 2. Opcional: Registrar no histórico (log) para saber quantas tentativas foram feitas
    // Isso é o que empresas de cartão fazem para medir produtividade
    $msg_log = "Tentativa registrada como: " . $status;
    $sql_log = "INSERT INTO historico_contatos (contato_id, desfecho, detalhes) VALUES (?, ?, ?)";
    $stmt_log = $conn->prepare($sql_log);
    $stmt_log->bind_param("iss", $id, $status, $msg_log);
    $stmt_log->execute();

    echo json_encode(["status" => "success", "message" => "Ciclo atualizado!"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

$stmt->close();
$conn->close();
?>