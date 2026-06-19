<?php
$paginaAtual = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sakura+ - Catálogo de Animes</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='12' fill='%23050505'/%3E%3Ctext x='32' y='42' font-size='30' text-anchor='middle' fill='%231652b8' font-family='Arial' font-weight='bold'%3ES%2B%3C/text%3E%3C/svg%3E">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container header-inner">
        <a href="home.php" class="logo">
            <span style="font-size:1.4rem;">🌸</span>
            <span>Sakura<strong>+</strong></span>
        </a>
        <nav>
            <a href="index.php" class="<?= $paginaAtual === 'index.php' ? 'ativo' : '' ?>">Catálogo</a>
            <a href="cadastro.php" class="btn-novo <?= $paginaAtual === 'cadastro.php' ? 'ativo' : '' ?>">+ Novo Anime</a>
        </nav>
    </div>
</header>

<main class="container">
