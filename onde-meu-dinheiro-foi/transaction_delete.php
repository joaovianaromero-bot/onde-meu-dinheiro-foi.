<?php
require_once 'includes/functions.php';
exigir_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirecionar('transactions.php');
verificar_csrf();

$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
if ($id) {
    $stmt = $pdo->prepare('DELETE FROM movimentacoes WHERE id = ? AND usuario_id = ?');
    $stmt->execute([$id, usuario_id()]);
    mensagem('Movimentação excluída.');
} else {
    mensagem('Movimentação inválida.', 'erro');
}
redirecionar('transactions.php');
