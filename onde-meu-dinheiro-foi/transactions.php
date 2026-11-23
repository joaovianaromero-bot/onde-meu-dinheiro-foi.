<?php
require_once 'includes/functions.php';
exigir_login();

$stmt = $pdo->prepare('SELECT id, nome, tipo FROM categorias WHERE usuario_id IS NULL OR usuario_id = ? ORDER BY nome');
$stmt->execute([usuario_id()]);
$categorias = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT m.*, c.nome AS categoria FROM movimentacoes m
    LEFT JOIN categorias c ON c.id = m.categoria_id
    WHERE m.usuario_id = ? ORDER BY m.data_movimentacao DESC, m.id DESC');
$stmt->execute([usuario_id()]);
$movimentacoes = $stmt->fetchAll();

$titulo = 'Movimentações';
require 'includes/header.php';
?>
<div class="page-heading">
    <div><p class="eyebrow">ENTRADAS E SAÍDAS</p><h1>Movimentações</h1><p class="muted">Registre e acompanhe seu dinheiro.</p></div>
</div>

<section class="panel form-panel">
    <h2>Adicionar movimentação</h2>
    <form action="transaction_save.php" method="post" class="transaction-form">
        <input type="hidden" name="csrf_token" value="<?= escapar(token_csrf()) ?>">
        <label>Tipo
            <select name="tipo" id="tipo" required>
                <option value="despesa">Despesa</option>
                <option value="receita">Receita</option>
            </select>
        </label>
        <label>Descrição
            <input type="text" name="descricao" maxlength="160" placeholder="Ex.: Compra no mercado" required>
        </label>
        <label>Valor (R$)
            <input type="number" name="valor" min="0.01" step="0.01" placeholder="0,00" required>
        </label>
        <label>Categoria
            <select name="categoria_id">
                <option value="">Sem categoria</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= (int)$cat['id'] ?>" data-tipo="<?= escapar($cat['tipo']) ?>"><?= escapar($cat['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Data
            <input type="date" name="data_movimentacao" value="<?= date('Y-m-d') ?>" required>
        </label>
        <button class="button primary" type="submit">Salvar movimentação</button>
    </form>
</section>

<section class="panel">
    <div class="panel-heading"><div><h2>Histórico</h2><p class="muted"><?= count($movimentacoes) ?> movimentação(ões)</p></div></div>
    <?php if (!$movimentacoes): ?>
        <div class="empty-state"><h3>Seu histórico está vazio</h3><p>As movimentações cadastradas aparecerão aqui.</p></div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Descrição</th><th>Categoria</th><th>Data</th><th>Tipo</th><th>Valor</th><th>Ação</th></tr></thead>
                <tbody>
                <?php foreach ($movimentacoes as $mov): ?>
                    <tr>
                        <td><strong><?= escapar($mov['descricao']) ?></strong></td>
                        <td><?= escapar($mov['categoria'] ?? '—') ?></td>
                        <td><?= date('d/m/Y', strtotime($mov['data_movimentacao'])) ?></td>
                        <td><span class="tag <?= $mov['tipo'] ?>"><?= ucfirst($mov['tipo']) ?></span></td>
                        <td class="<?= $mov['tipo'] === 'receita' ? 'positive' : 'negative' ?>"><?= dinheiro($mov['valor']) ?></td>
                        <td>
                            <form action="transaction_delete.php" method="post" data-confirm="Excluir esta movimentação?">
                                <input type="hidden" name="csrf_token" value="<?= escapar(token_csrf()) ?>">
                                <input type="hidden" name="id" value="<?= (int)$mov['id'] ?>">
                                <button class="text-button danger-text" type="submit">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
