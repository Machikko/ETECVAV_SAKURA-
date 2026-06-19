<?php

$servidor = "localhost";
$banco    = "animevault_db";
$usuario  = "root";
$senha    = "";

try {
    $conexao = new PDO(
        "mysql:host=$servidor;dbname=$banco;charset=utf8mb4",
        $usuario,
        $senha
    );
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $erro) {
    die("Erro na conexão com o banco de dados: " . $erro->getMessage());
}
