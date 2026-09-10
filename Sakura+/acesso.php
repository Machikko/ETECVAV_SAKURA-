<?php
require_once 'config/auth.php';
require_once 'includes/header.php';
?>

<h1>Acessar o Sakura+</h1>

<div class="form-card" style="max-width:580px;">
    <div class="form-acoes" style="margin-top:0;">
        <a href="login.php" class="btn-salvar" style="display:flex;align-items:center;justify-content:center;">Entrar</a>
        <a href="cadastro_cliente.php" class="btn-salvar" style="display:flex;align-items:center;justify-content:center;">Cadastrar como Cliente</a>
        <a href="cadastro_funcionario.php" class="btn-salvar" style="display:flex;align-items:center;justify-content:center;">Cadastrar como Funcionário</a>
        <a href="guest.php" class="btn btn-voltar" style="flex:none;display:flex;align-items:center;justify-content:center;padding:12px;">Usar como Guest</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
