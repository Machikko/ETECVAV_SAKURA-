<?php
require_once 'config/auth.php';
require_once 'config/conexao.php';

$erros = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = (string) ($_POST['senha'] ?? '');

    if ($email === '' || $senha === '') {
        $erros[] = 'Preencha e-mail e senha.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Informe um e-mail válido.';
    }

    if (empty($erros)) {
        $stmt = $conexao->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();

        if (!$usuario || !password_verify($senha, $usuario['senha'])) {
            $erros[] = 'E-mail ou senha inválidos.';
        } else {
            criarSessaoUsuario((int) $usuario['id'], $usuario['nome'], $usuario['tipo']);
            header('Location: index.php');
            exit;
        }
    }
}

require_once 'includes/header.php';
?>

<h1>Entrar</h1>

<?php if (!empty($erros)): ?>
    <div class="alerta alerta-erro">
        <?php foreach ($erros as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="form-card">
    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="text" id="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="seuemail@exemplo.com" required>
        </div>
        <div class="form-group">
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Sua senha" required>
        </div>
        <div class="form-acoes">
            <a href="acesso.php" class="btn btn-voltar">Cancelar</a>
            <button type="submit" class="btn-salvar">Entrar</button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
