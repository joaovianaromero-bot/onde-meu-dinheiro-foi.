<?php
require_once 'includes/functions.php';
exigir_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirecionar('goals.php');
verificar_csrf();

$titulo = trim($_POST['titulo'] ?? '');
$objetivo = filter_var($_POST['valor_objetivo'] ?? null, FILTER_VALIDATE_FLOAT);
$guardado = filter_var($_POST['valor_guardado'] ?? 0, FILTER_VALIDATE_FLOAT);
$prazo = trim($_POST['prazo'] ?? '');
$prazo = $prazo === '' ? null : $prazo;

$dataValida = $prazo === null || (DateTime::createFromFormat('Y-m-d', $prazo) && DateTime::createFromFormat('Y-m-d', $prazo)->format('Y-m-d') === $prazo);
if ($titulo === '' || strlen($titulo) > 120 || $objetivo === false || $objetivo <= 0 ||
    $guardado === false || $guardado < 0 || !$dataValida) {
    mensagem('Confira os dados da meta.', 'erro');
    redirecionar('goals.php');
}

$stmt = $pdo->prepare('INSERT INTO metas (usuario_id, titulo, valor_objetivo, valor_guardado, prazo) VALUES (?, ?, ?, ?, ?)');
$stmt->execute([usuario_id(), $titulo, $objetivo, $guardado, $prazo]);
mensagem('Meta criada com sucesso!');
redirecionar('goals.php');
