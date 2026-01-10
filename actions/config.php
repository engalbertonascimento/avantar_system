<?php
// Configurações do Banco de Dados
$db_host = "10.1.8.123";
$db_user = "avantar";
$db_pass = "avantar";
$db_name = "avantar";

// Criar a conexão
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Verificar se houve erro
if ($conn->connect_error) {
    // Em produção, não exiba o erro detalhado por segurança
    die("Falha na conexão: " . $conn->connect_error);
}

// Ajustar para aceitar acentuação corretamente (UTF-8)
$conn->set_charset("utf8mb4");
?>