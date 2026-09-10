<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function estaLogado(): bool
{
    return isset($_SESSION['usuario_tipo']);
}

function tipoUsuarioLogado(): ?string
{
    return $_SESSION['usuario_tipo'] ?? null;
}

function ehFuncionario(): bool
{
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'funcionario';
}

function ehCliente(): bool
{
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'cliente';
}

function ehGuest(): bool
{
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'guest';
}

function criarSessaoUsuario(int $id, string $nome, string $tipo): void
{
    $_SESSION['usuario_id']   = $id;
    $_SESSION['usuario_nome'] = $nome;
    $_SESSION['usuario_tipo'] = $tipo;
}

function criarSessaoGuest(): void
{
    $_SESSION['usuario_id']   = null;
    $_SESSION['usuario_nome'] = 'Visitante';
    $_SESSION['usuario_tipo'] = 'guest';
}

function exigirFuncionario(): void
{
    if (!ehFuncionario()) {
        header('Location: index.php?msg=acesso_negado');
        exit;
    }
}
