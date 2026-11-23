<?php
require_once 'includes/functions.php';

if (usuario_id()) redirecionar('index.php');

$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verificar_csrf();
    $nome = trim($_POST['nome'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $senha = $_POST['senha'] ?? '';

    if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8) {
        $erro = 'Preencha os campos corretamente. A senha precisa ter pelo menos 8 caracteres.';
    } else {
        try {
            $stmt = $pdo->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)');
            $stmt->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT)]);
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = (int)$pdo->lastInsertId();
            $_SESSION['usuario_nome'] = $nome;
            mensagem('Conta criada com sucesso!');
            redirecionar('index.php');
        } catch (PDOException $e) {
            $erro = 'Este e-mail já está cadastrado ou não pôde ser utilizado.';
        }
    }
}
$titulo = 'Criar conta';
require 'includes/header.php';
?>
<section class="auth-card">
    <p class="eyebrow">COMECE A SE ORGANIZAR</p>
    <h1>Criar conta</h1>
    <p class="muted">Cadastre-se para acompanhar seu dinheiro.</p>
    <?php if ($erro): ?><div class="alert erro"><?= escapar($erro) ?></div><?php endif; ?>
    <form method="post" class="form-grid">
        <input type="hidden" name="csrf_token" value="<?= escapar(token_csrf()) ?>">
        <label>Nome
            <input type="text" name="nome" maxlength="100" required value="<?= escapar($_POST['nome'] ?? '') ?>">
        </label>
        <label>E-mail
            <input type="email" name="email" maxlength="150" required value="<?= escapar($_POST['email'] ?? '') ?>">
        </label>
        <label>Senha
            <input type="password" name="senha" minlength="8" required>
        </label>
        <button class="button primary" type="submit">Criar minha conta</button>
    </form>
    <p class="auth-link">Já tem conta? <a href="login.php">Entrar</a></p>
</section>
<?php require 'includes/footer.php'; ?>
