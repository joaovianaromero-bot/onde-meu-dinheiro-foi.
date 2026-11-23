<?php
require_once 'includes/functions.php';
exigir_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirecionar('transactions.php');
verificar_csrf();

$tipo = $_POST['tipo'] ?? '';
$descricao = trim($_POST['descricao'] ?? '');
$valor = filter_var($_POST['valor'] ?? null, FILTER_VALIDATE_FLOAT);
$data = $_POST['data_movimentacao'] ?? '';
$categoria = ($_POST['categoria_id'] ?? '') !== '' ? (int)$_POST['categoria_id'] : null;

$dataValida = DateTime::createFromFormat('Y-m-d', $data);
if (!in_array($tipo, ['receita', 'despesa'], true) || $descricao === '' ||
    strlen($descricao) > 160 || $valor === false || $valor <= 0 ||
    !$dataValida || $dataValida->format('Y-m-d') !== $data) {
    mensagem('Confira os dados da movimentação.', 'erro');
    redirecionar('transactions.php');
}

if ($categoria !== null) {
    $stmt = $pdo->prepare('SELECT id FROM categorias WHERE id = ? AND tipo = ? AND (usuario_id IS NULL OR usuario_id = ?)');
    $stmt->execute([$categoria, $tipo, usuario_id()]);
    if (!$stmt->fetch()) $categoria = null;
}

$stmt = $pdo->prepare('INSERT INTO movimentacoes (usuario_id, categoria_id, tipo, descricao, valor, data_movimentacao) VALUES (?, ?, ?, ?, ?, ?)');
$stmt->execute([usuario_id(), $categoria, $tipo, $descricao, $valor, $data]);
mensagem('Movimentação cadastrada!');
redirecionar('transactions.php');
