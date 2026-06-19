<?php
require_once 'config/conexao.php';
require_once 'config/funcoes.php';

$busca = trim($_GET['busca'] ?? '');

if ($busca !== '') {
    
    $stmt = $conexao->prepare("SELECT * FROM animes WHERE nome LIKE ? ORDER BY nome ASC");
    $stmt->execute(["%$busca%"]);
} else {
    $stmt = $conexao->query("SELECT * FROM animes ORDER BY nome ASC");
}

$animes   = $stmt->fetchAll();
$mensagem = $_GET['msg'] ?? '';

require_once 'includes/header.php';
?>

<h1>Catálogo de Animes</h1>

<?php if ($mensagem === 'cadastrado'): ?>
    <div class="alerta alerta-sucesso">Anime cadastrado com sucesso!</div>
<?php elseif ($mensagem === 'editado'): ?>
    <div class="alerta alerta-sucesso">Anime atualizado com sucesso!</div>
<?php elseif ($mensagem === 'excluido'): ?>
    <div class="alerta alerta-sucesso">Anime removido do catálogo.</div>
<?php endif; ?>

<form class="barra-busca" method="GET" action="index.php">
    <input type="text" name="busca" placeholder="Buscar anime pelo nome..." value="<?= htmlspecialchars($busca) ?>">
    <button type="submit">Buscar</button>
</form>

<?php if (empty($animes)): ?>

    <div class="empty-state">
        <span style="font-size:2.4rem; display:block; margin-bottom:12px;">🔍</span>
        <p>Nenhum anime encontrado<?= $busca !== '' ? ' para "' . htmlspecialchars($busca) . '"' : '' ?>.</p>
    </div>

<?php else: ?>

    <div class="cards-grid">
        <?php foreach ($animes as $anime): ?>
        <div class="card">
            <a href="anime.php?id=<?= $anime['id'] ?>" class="card-link">
                <div class="card-imagem">
                    <img src="<?= htmlspecialchars($anime['imagem']) ?>" alt="Capa do anime <?= htmlspecialchars($anime['nome']) ?>">
                    <span class="card-ano"><?= htmlspecialchars((string) $anime['ano_lancamento']) ?></span>
                </div>
                <div class="card-corpo">
                    <div class="card-nome"><?= htmlspecialchars($anime['nome']) ?></div>
                    <div class="card-genero"><?= htmlspecialchars($anime['genero']) ?></div>
                    <div class="card-stats">
                        <span class="badge badge-views">👁️ <?= formatarNumeroCompacto((int) $anime['visualizacoes']) ?></span>
                        <span class="badge badge-likes">❤️ <?= formatarNumeroCompacto((int) $anime['likes']) ?></span>
                    </div>
                </div>
            </a>
            <div class="card-corpo" style="padding-top:0;">
                <div class="card-acoes">
                    <a href="editar.php?id=<?= $anime['id'] ?>" class="btn btn-editar">Editar</a>
                    <a href="excluir.php?id=<?= $anime['id'] ?>" class="btn btn-excluir">Excluir</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
