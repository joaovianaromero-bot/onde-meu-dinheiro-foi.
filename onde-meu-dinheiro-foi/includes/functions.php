<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

function escapar($valor): string {
    return htmlspecialchars((string)$valor, ENT_QUOTES, 'UTF-8');
}

function usuario_id(): ?int {
    return isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : null;
}

function exigir_login(): void {
    if (!usuario_id()) {
        header('Location: login.php');
        exit;
    }
}

function token_csrf(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verificar_csrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(token_csrf(), $token)) {
        http_response_code(403);
        exit('Solicitação inválida. Atualize a página e tente novamente.');
    }
}

function mensagem(string $texto, string $tipo = 'sucesso'): void {
    $_SESSION['mensagem'] = ['texto' => $texto, 'tipo' => $tipo];
}

function redirecionar(string $pagina): never {
    header("Location: {$pagina}");
    exit;
}

function dinheiro($valor): string {
    return 'R$ ' . number_format((float)$valor, 2, ',', '.');
}
