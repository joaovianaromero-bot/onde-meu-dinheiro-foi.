<?php
require_once 'includes/functions.php';

if (usuario_id()) redirecionar('index.php');

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();
    $email = strtolower(trim($_POST['email'] ?? ''));
    $senha = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare('SELECT id, nome, senha FROM usuarios WHERE email = ?');
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = (int)$usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        mensagem('Bem-vindo de volta!');
        redirecionar('index.php');
    } else {
        $erro = 'E-mail ou senha incorretos.';
    }
}
$titulo = 'Entrar';
require 'includes/header.php';
?>
<section class="auth-card">
    <p class="eyebrow">SEU DINHEIRO, SOB CONTROLE</p>
    <h1>Entrar</h1>
    <p class="muted">Acesse seu controle financeiro.</p>
    <?php if ($erro): ?><div class="alert erro"><?= escapar($erro) ?></div><?php endif; ?>
    <form method="post" class="form-grid">
        <input type="hidden" name="csrf_token" value="<?= escapar(token_csrf()) ?>">
        <label>E-mail
            <input type="email" name="email" required autocomplete="email" value="<?= escapar($_POST['email'] ?? '') ?>">
        </label>
        <label>Senha
            <input type="password" name="senha" required autocomplete="current-password">
        </label>
        <button class="button primary" type="submit">Entrar</button>
    </form>
    <p class="auth-link">Ainda não tem conta? <a href="register.php">Criar conta</a></p>
</section>
<?php require 'includes/footer.php'; ?>
