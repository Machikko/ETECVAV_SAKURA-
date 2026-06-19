<?php
require_once 'config/conexao.php';
require_once 'config/funcoes.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conexao->prepare("DELETE FROM animes WHERE id = ?");
    $stmt->execute([$id]);

    removerImagemAntiga($anime['imagem']);

    header('Location: index.php?msg=excluido');
    exit;
}

require_once 'includes/header.php';
?>

<h1>Confirmar Exclusão</h1>

<div class="form-card confirmacao-card">
    <div class="confirmacao-imagem">
        <img src="<?= htmlspecialchars($anime['imagem']) ?>" alt="Capa de <?= htmlspecialchars($anime['nome']) ?>">
    </div>
    <p class="confirmacao-texto">
        Tem certeza de que deseja excluir o anime
        <strong style="color:#fff;"><?= htmlspecialchars($anime['nome']) ?></strong> do catálogo?
        Esta ação não pode ser desfeita.
    </p>

    <form method="POST" action="excluir.php?id=<?= $id ?>">
        <input type="hidden" name="id" value="<?= $id ?>">
        <div class="form-acoes">
            <a href="index.php" class="btn btn-voltar">Cancelar</a>
            <button type="submit" class="btn-confirmar-exclusao">Sim, excluir anime</button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
