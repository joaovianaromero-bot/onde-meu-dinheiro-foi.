<?php
$host = 'localhost';
$dbname = 'onde_meu_dinheiro_foi';
$username = 'root';
$password = ''; // No XAMPP, normalmente fica vazio. Altere se necessário.

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('Não foi possível conectar ao banco de dados. Confira config/database.php e importe database.sql.');
}
