<?php
require_once 'config.php';
header('Content-Type: application/json');

$dados = [
    'nome'      => $_POST['nome_cliente'],
    'final_tel' => $_POST['final_telefone'],
    'status'    => $_POST['status_contato'],
    'cnpj'      => $_POST['cnpj'],
    'vidas'     => $_POST['qtd_vidas'],
    'operadora' => $_POST['operadora'],
    'd_inicio'  => $_POST['data_primeiro_contato'],
    'd_prevista'=> $_POST['data_retorno_prevista'],
    'd_cliente' => $_POST['data_solicitada_cliente'],
    'valor'     => $_POST['valor_cotacao'],
    'obs'       => $_POST['observacoes']
];

$sql = "INSERT INTO contatos (nome_cliente, final_telefone, status_contato, cnpj, qtd_vidas, operadora, 
        data_primeiro_contato, data_retorno_prevista, data_solicitada_cliente, valor_cotacao, observacoes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssissssds", 
    $dados['nome'], $dados['final_tel'], $dados['status'], $dados['cnpj'], $dados['vidas'], 
    $dados['operadora'], $dados['d_inicio'], $dados['d_prevista'], $dados['d_cliente'], 
    $dados['valor'], $dados['obs']
);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Contato salvo com a regra de 3 dias!"]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>