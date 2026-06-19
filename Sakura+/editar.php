<?php
require_once 'config/conexao.php';
require_once 'config/funcoes.php';

$erros = [];
$id    = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

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
    $dados = [
        'nome'           => trim($_POST['nome'] ?? ''),
        'genero'         => trim($_POST['genero'] ?? ''),
        'ano_lancamento' => trim($_POST['ano_lancamento'] ?? ''),
        'visualizacoes'  => trim($_POST['visualizacoes'] ?? '0'),
        'likes'          => trim($_POST['likes'] ?? '0'),
    ];

    $erros = validarDadosAnime($dados);

 
    $imagemFinal     = $anime['imagem'];
    $resultadoUpload = processarUploadImagem($_FILES['imagem'] ?? [], obrigatorio: false);

    if ($resultadoUpload['erro'] !== null) {
        $erros[] = $resultadoUpload['erro'];
    }


    $anime = array_merge($anime, $dados);

    if (empty($erros)) {
        if ($resultadoUpload['nomeArquivo'] !== null) {
            removerImagemAntiga($anime['imagem']);
            $imagemFinal = $resultadoUpload['nomeArquivo'];
        }

        $stmt = $conexao->prepare(
            "UPDATE animes
                SET nome = ?, genero = ?, ano_lancamento = ?, visualizacoes = ?, likes = ?, imagem = ?
              WHERE id = ?"
        );
        $stmt->execute([
            $dados['nome'],
            $dados['genero'],
            (int) $dados['ano_lancamento'],
            (int) $dados['visualizacoes'],
            (int) $dados['likes'],
            $imagemFinal,
            $id,
        ]);

        header('Location: index.php?msg=editado');
        exit;
    }

    if ($resultadoUpload['nomeArquivo'] !== null) {
        removerImagemAntiga($resultadoUpload['nomeArquivo']);
    }
}

require_once 'includes/header.php';
?>

<h1>Editar Anime</h1>

<?php if (!empty($erros)): ?>
    <div class="alerta alerta-erro">
        <?php foreach ($erros as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <div class="imagem-atual">
        <img src="<?= htmlspecialchars($anime['imagem']) ?>" alt="Capa atual de <?= htmlspecialchars($anime['nome']) ?>">
        <span style="font-size:0.85rem; color:var(--texto-suave); font-weight:700;">Imagem atual</span>
    </div>

    <form method="POST" action="editar.php?id=<?= $id ?>" enctype="multipart/form-data">
        <div class="form-grid-2">
            <div class="form-group">
                <label for="nome">Nome do Anime</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($anime['nome']) ?>" required>
            </div>
            <div class="form-group">
                <label for="genero">Gênero</label>
                <input type="text" id="genero" name="genero" value="<?= htmlspecialchars($anime['genero']) ?>" required>
            </div>
            <div class="form-group">
                <label for="ano_lancamento">Ano de Lançamento</label>
                <input type="number" id="ano_lancamento" name="ano_lancamento" value="<?= htmlspecialchars((string) $anime['ano_lancamento']) ?>" min="1900" max="<?= date('Y') + 1 ?>" required>
            </div>
            <div class="form-group">
                <label for="visualizacoes">Visualizações</label>
                <input type="number" id="visualizacoes" name="visualizacoes" value="<?= htmlspecialchars((string) $anime['visualizacoes']) ?>" min="0" required>
            </div>
            <div class="form-group">
                <label for="likes">Likes</label>
                <input type="number" id="likes" name="likes" value="<?= htmlspecialchars((string) $anime['likes']) ?>" min="0" required>
            </div>
            <div class="form-group">
                <label for="imagem">Substituir Imagem (opcional)</label>
                <input type="file" id="imagem" name="imagem" accept=".jpg,.jpeg,.png,.webp,.gif">
            </div>
        </div>
        <div class="form-acoes">
            <a href="index.php" class="btn btn-voltar">Cancelar</a>
            <button type="submit" class="btn-salvar">Salvar Alterações</button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
