<?php
require_once 'config/conexao.php';
require_once 'config/funcoes.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: index.php');
    exit;
}

$stmt = $conexao->prepare("SELECT * FROM animes WHERE id = ?");
$stmt->execute([$id]);
$anime = $stmt->fetch();

if (!$anime) {
    header('Location: index.php');
    exit;
}


$conexao->prepare("UPDATE animes SET visualizacoes = visualizacoes + 1 WHERE id = ?")->execute([$id]);
$anime['visualizacoes']++;

require_once 'includes/header.php';
?>

<h1><?= htmlspecialchars($anime['nome']) ?></h1>

<div class="anime-detalhe">
    <div class="anime-detalhe-imagem">
        <img src="<?= htmlspecialchars($anime['imagem']) ?>" alt="Capa do anime <?= htmlspecialchars($anime['nome']) ?>">
    </div>
    <div class="anime-detalhe-corpo">
        <div class="anime-detalhe-nome"><?= htmlspecialchars($anime['nome']) ?></div>
        <span class="anime-detalhe-genero"><?= htmlspecialchars($anime['genero']) ?></span>

        <div class="anime-detalhe-meta">
            <span>📅 Lançamento: <?= htmlspecialchars((string) $anime['ano_lancamento']) ?></span>
            <span>👁️ <span id="contador-views"><?= number_format((int) $anime['visualizacoes'], 0, ',', '.') ?></span> visualizações</span>
            <span>❤️ <span id="contador-likes"><?= number_format((int) $anime['likes'], 0, ',', '.') ?></span> likes</span>
        </div>

        <div class="anime-detalhe-acoes">
            <button type="button" id="btn-like" class="btn-like" data-id="<?= $anime['id'] ?>">
                <span id="icone-like">❤️</span> Curtir
            </button>
            <a href="index.php" class="btn btn-voltar" style="flex:none;padding:12px 24px;">Voltar ao catálogo</a>
        </div>
    </div>
</div>

<script>
document.getElementById('btn-like').addEventListener('click', function () {
    const botao = this;
    const id = botao.dataset.id;

    botao.disabled = true;

    fetch('curtir.php?id=' + encodeURIComponent(id), { method: 'POST' })
        .then(function (resposta) { return resposta.json(); })
        .then(function (dados) {
            if (dados.sucesso) {
                document.getElementById('contador-likes').textContent =
                    dados.likes.toLocaleString('pt-BR');
                botao.classList.add('curtido');
            }
        })
        .finally(function () {
            botao.disabled = false;
        });
});
</script>

<?php require_once 'includes/footer.php'; ?>
