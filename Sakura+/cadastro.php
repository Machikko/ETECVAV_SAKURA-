<?php
require_once 'config/conexao.php';
require_once 'config/funcoes.php';

$erros = [];
$dados = [
    'nome'           => '',
    'genero'         => '',
    'ano_lancamento' => '',
    'visualizacoes'  => '0',
    'likes'          => '0',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados['nome']           = trim($_POST['nome'] ?? '');
    $dados['genero']         = trim($_POST['genero'] ?? '');
    $dados['ano_lancamento'] = trim($_POST['ano_lancamento'] ?? '');
    $dados['visualizacoes']  = trim($_POST['visualizacoes'] ?? '0');
    $dados['likes']          = trim($_POST['likes'] ?? '0');

    $erros = validarDadosAnime($dados);

    $resultadoUpload = processarUploadImagem($_FILES['imagem'] ?? [], obrigatorio: true);
    if ($resultadoUpload['erro'] !== null) {
        $erros[] = $resultadoUpload['erro'];
    }

    if (empty($erros)) {
        $stmt = $conexao->prepare(
            "INSERT INTO animes (nome, genero, ano_lancamento, visualizacoes, likes, imagem)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $dados['nome'],
            $dados['genero'],
            (int) $dados['ano_lancamento'],
            (int) $dados['visualizacoes'],
            (int) $dados['likes'],
            $resultadoUpload['nomeArquivo'],
        ]);

        header('Location: index.php?msg=cadastrado');
        exit;
    }


    
    if ($resultadoUpload['nomeArquivo'] !== null) {
        removerImagemAntiga($resultadoUpload['nomeArquivo']);
    }
}

require_once 'includes/header.php';
?>

<h1>Cadastrar Novo Anime</h1>

<?php if (!empty($erros)): ?>
    <div class="alerta alerta-erro">
        <?php foreach ($erros as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <form method="POST" action="cadastro.php" enctype="multipart/form-data">
        <div class="form-grid-2">
            <div class="form-group">
                <label for="nome">Nome do Anime</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($dados['nome']) ?>" placeholder="Ex: Attack on Titan" required>
            </div>
            <div class="form-group">
                <label for="genero">Gênero</label>
                <input type="text" id="genero" name="genero" value="<?= htmlspecialchars($dados['genero']) ?>" placeholder="Ex: Ação/Aventura" required>
            </div>
            <div class="form-group">
                <label for="ano_lancamento">Ano de Lançamento</label>
                <input type="number" id="ano_lancamento" name="ano_lancamento" value="<?= htmlspecialchars($dados['ano_lancamento']) ?>" min="1900" max="<?= date('Y') + 1 ?>" placeholder="Ex: 2013" required>
            </div>
            <div class="form-group">
                <label for="visualizacoes">Visualizações</label>
                <input type="number" id="visualizacoes" name="visualizacoes" value="<?= htmlspecialchars($dados['visualizacoes']) ?>" min="0" placeholder="0" required>
            </div>
            <div class="form-group">
                <label for="likes">Likes</label>
                <input type="number" id="likes" name="likes" value="<?= htmlspecialchars($dados['likes']) ?>" min="0" placeholder="0" required>
            </div>
            <div class="form-group">
                <label for="imagem">Imagem do Anime</label>
                <input type="file" id="imagem" name="imagem" accept=".jpg,.jpeg,.png,.webp,.gif" required>
            </div>
        </div>
        <div class="form-acoes">
            <a href="index.php" class="btn btn-voltar">Cancelar</a>
            <button type="submit" class="btn-salvar">Cadastrar Anime</button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
