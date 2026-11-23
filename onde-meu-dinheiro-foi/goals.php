<?php
require_once 'includes/functions.php';
exigir_login();

$stmt = $pdo->prepare('SELECT * FROM metas WHERE usuario_id = ? ORDER BY criado_em DESC');
$stmt->execute([usuario_id()]);
$metas = $stmt->fetchAll();

$titulo = 'Metas';
require 'includes/header.php';
?>
<div class="page-heading">
    <div><p class="eyebrow">PLANOS PARA O FUTURO</p><h1>Metas de economia</h1><p class="muted">Acompanhe o quanto falta para alcançar seus objetivos.</p></div>
</div>

<section class="panel form-panel">
    <h2>Criar uma meta</h2>
    <form action="goal_save.php" method="post" class="transaction-form goal-form">
        <input type="hidden" name="csrf_token" value="<?= escapar(token_csrf()) ?>">
        <label>Nome da meta
            <input type="text" name="titulo" maxlength="120" placeholder="Ex.: Comprar um computador" required>
        </label>
        <label>Valor desejado (R$)
            <input type="number" name="valor_objetivo" min="0.01" step="0.01" required>
        </label>
        <label>Quanto já guardou? (R$)
            <input type="number" name="valor_guardado" min="0" step="0.01" value="0">
        </label>
        <label>Prazo (opcional)
            <input type="date" name="prazo">
        </label>
        <button class="button primary" type="submit">Salvar meta</button>
    </form>
</section>

<section class="goal-grid">
    <?php if (!$metas): ?>
        <article class="panel empty-state"><h3>Nenhuma meta cadastrada</h3><p>Crie uma meta para acompanhar seu progresso.</p></article>
    <?php else: foreach ($metas as $meta):
        $percentual = min(100, ((float)$meta['valor_guardado'] / (float)$meta['valor_objetivo']) * 100);
    ?>
        <article class="panel goal-card">
            <div class="goal-top"><h2><?= escapar($meta['titulo']) ?></h2><span><?= number_format($percentual, 0) ?>%</span></div>
            <div class="progress"><span style="width: <?= $percentual ?>%"></span></div>
            <p><strong><?= dinheiro($meta['valor_guardado']) ?></strong> de <?= dinheiro($meta['valor_objetivo']) ?></p>
            <small class="muted"><?= $meta['prazo'] ? 'Prazo: ' . date('d/m/Y', strtotime($meta['prazo'])) : 'Sem prazo definido' ?></small>
        </article>
    <?php endforeach; endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
