<?php
require_once 'config/auth.php';
require_once 'config/conexao.php';

$tipoConta = 'cliente';
$tituloPagina = 'Cadastrar como Cliente';

$erros = [];
$dados = ['nome' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados['nome']       = trim($_POST['nome'] ?? '');
    $dados['email']      = trim($_POST['email'] ?? '');
    $senha               = (string) ($_POST['senha'] ?? '');
    $confirmarSenha      = (string) ($_POST['confirmar_senha'] ?? '');

    if ($dados['nome'] === '') {
        $erros[] = 'O nome é obrigatório.';
    }

    if ($dados['email'] === '') {
        $erros[] = 'O e-mail é obrigatório.';
    } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    }

    if ($senha === '' || $confirmarSenha === '') {
        $erros[] = 'Preencha a senha e a confirmação de senha.';
    } elseif (strlen($senha) < 6) {
        $erros[] = 'A senha deve ter pelo menos 6 caracteres.';
    } elseif ($senha !== $confirmarSenha) {
        $erros[] = 'As senhas não coincidem.';
    }

    if (empty($erros)) {
        $stmt = $conexao->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$dados['email']]);
        if ($stmt->fetch()) {
            $erros[] = 'Já existe uma conta cadastrada com este e-mail.';
        }
    }

    if (empty($erros)) {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $stmt = $conexao->prepare(
            "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$dados['nome'], $dados['email'], $senhaHash, $tipoConta]);

        criarSessaoUsuario((int) $conexao->lastInsertId(), $dados['nome'], $tipoConta);

        header('Location: index.php');
        exit;
    }
}

require_once 'includes/header.php';
?>

<h1><?= htmlspecialchars($tituloPagina) ?></h1>

<?php if (!empty($erros)): ?>
    <div class="alerta alerta-erro">
        <?php foreach ($erros as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <form method="POST" action="cadastro_cliente.php">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($dados['nome']) ?>" placeholder="Seu nome completo" required>
        </div>
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($dados['email']) ?>" placeholder="seuemail@exemplo.com" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Mínimo 6 caracteres" required>
        </div>
        <div class="form-group">
            <label for="confirmar_senha">Confirmar Senha</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Repita a senha" required>
        </div>
        <div class="form-acoes">
            <a href="acesso.php" class="btn btn-voltar">Cancelar</a>
            <button type="submit" class="btn-salvar">Cadastrar</button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
