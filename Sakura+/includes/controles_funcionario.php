<?php
if (isset($animeIdControle)):
?>
    <div class="card-acoes">
        <a href="editar.php?id=<?= (int) $animeIdControle ?>" class="btn btn-editar">Editar</a>
        <a href="excluir.php?id=<?= (int) $animeIdControle ?>" class="btn btn-excluir">Excluir</a>
    </div>
<?php else: ?>
    <a href="cadastro.php" class="btn-novo <?= (isset($paginaAtual) && $paginaAtual === 'cadastro.php') ? 'ativo' : '' ?>">+ Novo Anime</a>
<?php endif; ?>
