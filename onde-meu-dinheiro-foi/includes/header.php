<?php
$titulo = $titulo ?? 'Painel';
$nomeUsuario = $_SESSION['usuario_nome'] ?? 'Usuário';
$flash = $_SESSION['mensagem'] ?? null;
unset($_SESSION['mensagem']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escapar($titulo) ?> | Onde Meu Dinheiro Foi?</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<header class="topbar">
    <a class="brand" href="index.php">Onde Meu Dinheiro Foi?</a>
    <nav>
        <a href="index.php">Painel</a>
        <a href="transactions.php">Movimentações</a>
        <a href="goals.php">Metas</a>
        <form action="logout.php" method="post" class="logout-form">
            <input type="hidden" name="csrf_token" value="<?= escapar(token_csrf()) ?>">
            <button class="nav-button" type="submit">Sair</button>
        </form>
    </nav>
</header>
<main class="container">
    <?php if ($flash): ?>
        <div class="alert <?= escapar($flash['tipo']) ?>"><?= escapar($flash['texto']) ?></div>
    <?php endif; ?>
