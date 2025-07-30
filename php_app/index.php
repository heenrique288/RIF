<?php

require_once 'controller/AlunoController.php'; // Ajuste o caminho se necessário

// Detalhes da conexão com o banco de dados
$host = 'localhost';
$db   = 'seu_nome_do_banco_de_dados'; // Substitua pelo nome do seu banco de dados
$user = 'seu_usuario';     // Substitua pelo seu usuário do banco de dados
$pass = 'sua_senha';     // Substitua pela sua senha do banco de dados
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$controller = new AlunoController($pdo);

// Roteamento simples baseado no parâmetro 'action' da URL
$action = $_GET['action'] ?? 'list'; // A ação padrão é 'list'

$controller->handleRequest($action);

?>