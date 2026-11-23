<?php
require_once 'includes/functions.php';
exigir_login();

$id = usuario_id();
$stmt = $pdo->prepare("SELECT
    COALESCE(SUM(CASE WHEN tipo='receita' THEN valor ELSE 0 END), 0) AS receitas,
    COALESCE(SUM(CASE WHEN tipo='despesa' THEN valor ELSE 0 END), 0) AS despesas
    FROM movimentacoes WHERE usuario_id = ?");
$stmt->execute([$id]);
$totais = $stmt->fetch();
$saldo = (float)$totais['receitas'] - (float)$totais['despesas'];

$stmt = $pdo->prepare('SELECT m.*, c.nome AS categoria FROM movimentacoes m
    LEFT JOIN categorias c ON c.id = m.categoria_id
    WHERE m.usuario_id = ? ORDER BY m.data_movimentacao DESC, m.id DESC LIMIT 6');
$stmt->execute([$id]);
$ultimas = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT DATE_FORMAT(data_movimentacao, '%Y-%m') AS mes,
    SUM(CASE WHEN tipo='receita' THEN valor ELSE 0 END) AS receitas,
    SUM(CASE WHEN tipo='despesa' THEN valor ELSE 0 END) AS despesas
    FROM movimentacoes WHERE usuario_id = ? AND data_movimentacao >= DATE_SUB(CURDATE(), INTERVAL 5 MONTH)
    GROUP BY DATE_FORMAT(data_movimentacao, '%Y-%m') ORDER BY mes");
$stmt->execute([$id]);
$meses = $stmt->fetchAll();

$titulo = 'Painel financeiro';
require 'includes/header.php';
?>
<div class="page-heading">
    <div>
        <p class="eyebrow">VISÃO GERAL</p>
        <h1>Olá, <?= escapar($_SESSION['usuario_nome']) ?>!</h1>
        <p class="muted">Aqui está um resumo das suas movimentações.</p>
    </div>
    <a class="button primary" href="transactions.php">+ Nova movimentação</a>
</div>

<section class="summary-grid">
    <article class="summary-card balance">
        <span>Saldo disponível</span>
        <strong><?= dinheiro($saldo) ?></strong>
        <small>Receitas menos despesas</small>
    </article>
    <article class="summary-card">
        <span>Total de receitas</span>
        <strong class="positive"><?= dinheiro($totais['receitas']) ?></strong>
        <small>Todo o período</small>
    </article>
    <article class="summary-card">
        <span>Total de despesas</span>
        <strong class="negative"><?= dinheiro($totais['despesas']) ?></strong>
        <small>Todo o período</small>
    </article>
</section>

<section class="content-grid">
    <article class="panel">
        <div class="panel-heading">
            <div><h2>Movimentações recentes</h2><p class="muted">Suas últimas entradas e saídas</p></div>
            <a href="transactions.php">Ver todas</a>
        </div>
        <?php if (!$ultimas): ?>
            <div class="empty-state"><span class="empty-icon">↗</span><h3>Nenhuma movimentação ainda</h3><p>Cadastre sua primeira receita ou despesa.</p></div>
        <?php else: ?>
            <div class="transaction-list">
                <?php foreach ($ultimas as $mov): ?>
                    <div class="transaction-row">
                        <div class="transaction-icon <?= $mov['tipo'] === 'receita' ? 'income' : 'expense' ?>">
                            <?= $mov['tipo'] === 'receita' ? '+' : '−' ?>
                        </div>
                        <div class="transaction-info">
                            <strong><?= escapar($mov['descricao']) ?></strong>
                            <small><?= escapar($mov['categoria'] ?? 'Sem categoria') ?> · <?= date('d/m/Y', strtotime($mov['data_movimentacao'])) ?></small>
                        </div>
                        <strong class="<?= $mov['tipo'] === 'receita' ? 'positive' : 'negative' ?>">
                            <?= $mov['tipo'] === 'receita' ? '+' : '−' ?> <?= dinheiro($mov['valor']) ?>
                        </strong>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </article>

    <article class="panel">
        <div class="panel-heading"><div><h2>Últimos meses</h2><p class="muted">Receitas e despesas</p></div></div>
        <?php if (!$meses): ?>
            <div class="empty-state compact"><p>Os gráficos aparecerão quando você cadastrar movimentações.</p></div>
        <?php else: ?>
            <?php
            $maximo = 1;
            foreach ($meses as $m) $maximo = max($maximo, (float)$m['receitas'], (float)$m['despesas']);
            ?>
            <div class="chart-legend"><span><i class="dot income-dot"></i> Receitas</span><span><i class="dot expense-dot"></i> Despesas</span></div>
            <div class="bar-chart">
                <?php foreach ($meses as $m): ?>
                    <div class="bar-group">
                        <div class="bars">
                            <div class="bar income-bar" style="height: <?= max(3, (float)$m['receitas'] / $maximo * 100) ?>%" title="Receitas: <?= dinheiro($m['receitas']) ?>"></div>
                            <div class="bar expense-bar" style="height: <?= max(3, (float)$m['despesas'] / $maximo * 100) ?>%" title="Despesas: <?= dinheiro($m['despesas']) ?>"></div>
                        </div>
                        <small><?= date('m/y', strtotime($m['mes'] . '-01')) ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </article>
</section>
<?php require 'includes/footer.php'; ?>
