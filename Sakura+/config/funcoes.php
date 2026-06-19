<?php



define('PASTA_UPLOAD', __DIR__ . '/../img/uploads/');

define('CAMINHO_UPLOAD_WEB', 'img/uploads/');


const EXTENSOES_PERMITIDAS = ['jpg', 'jpeg', 'png', 'webp', 'gif'];


const TAMANHO_MAXIMO_UPLOAD = 5 * 1024 * 1024;

/**
 * Valida os campos textuais/numéricos comuns ao formulário de anime.
 *
 * @param array $dados Dados já tratados (nome, genero, ano_lancamento, visualizacoes, likes)
 * @return array Lista de mensagens de erro (vazia se tudo estiver válido)
 */
function validarDadosAnime(array $dados): array
{
    $erros = [];
    $anoAtual = (int) date('Y');

    if ($dados['nome'] === '') {
        $erros[] = 'O nome do anime é obrigatório.';
    } elseif (mb_strlen($dados['nome']) > 150) {
        $erros[] = 'O nome do anime deve ter no máximo 150 caracteres.';
    }

    if ($dados['genero'] === '') {
        $erros[] = 'O gênero é obrigatório.';
    } elseif (mb_strlen($dados['genero']) > 100) {
        $erros[] = 'O gênero deve ter no máximo 100 caracteres.';
    }

    if (!ctype_digit((string) $dados['ano_lancamento'])) {
        $erros[] = 'Informe um ano de lançamento válido.';
    } elseif ((int) $dados['ano_lancamento'] < 1900 || (int) $dados['ano_lancamento'] > $anoAtual + 1) {
        $erros[] = "O ano de lançamento deve estar entre 1900 e " . ($anoAtual + 1) . ".";
    }

    if (!ctype_digit((string) $dados['visualizacoes'])) {
        $erros[] = 'Informe um número válido de visualizações (apenas números inteiros).';
    }

    if (!ctype_digit((string) $dados['likes'])) {
        $erros[] = 'Informe um número válido de likes (apenas números inteiros).';
    }

    return $erros;
}

/**
 * Valida e move um arquivo de imagem enviado via $_FILES para a pasta de uploads.
 *
 * @param array $arquivo Elemento de $_FILES (ex.: $_FILES['imagem'])
 * @param bool  $obrigatorio Se true, a ausência de arquivo é considerada erro.
 * @return array{erro: ?string, nomeArquivo: ?string} erro = null quando tudo ocorreu bem;
 *                                                     nomeArquivo = novo nome salvo em disco (ou null)
 */
function processarUploadImagem(array $arquivo, bool $obrigatorio = true): array
{
    $semArquivoEnviado = !isset($arquivo['error']) || $arquivo['error'] === UPLOAD_ERR_NO_FILE;

    if ($semArquivoEnviado) {
        if ($obrigatorio) {
            return ['erro' => 'A imagem do anime é obrigatória.', 'nomeArquivo' => null];
        }
        return ['erro' => null, 'nomeArquivo' => null];
    }

    if ($arquivo['error'] !== UPLOAD_ERR_OK) {
        return ['erro' => 'Ocorreu um erro ao enviar a imagem. Tente novamente.', 'nomeArquivo' => null];
    }

    if ($arquivo['size'] > TAMANHO_MAXIMO_UPLOAD) {
        return ['erro' => 'A imagem deve ter no máximo 5 MB.', 'nomeArquivo' => null];
    }

    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    if (!in_array($extensao, EXTENSOES_PERMITIDAS, true)) {
        return ['erro' => 'Formato de imagem inválido. Use JPG, JPEG, PNG, WEBP ou GIF.', 'nomeArquivo' => null];
    }

    $infoImagem = @getimagesize($arquivo['tmp_name']);
    if ($infoImagem === false) {
        return ['erro' => 'O arquivo enviado não é uma imagem válida.', 'nomeArquivo' => null];
    }

    if (!is_dir(PASTA_UPLOAD) && !mkdir(PASTA_UPLOAD, 0755, true) && !is_dir(PASTA_UPLOAD)) {
        return ['erro' => 'Não foi possível preparar a pasta de upload no servidor.', 'nomeArquivo' => null];
    }

    $nomeUnico = uniqid('anime_', true) . '.' . $extensao;
    $destino   = PASTA_UPLOAD . $nomeUnico;

    if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
        return ['erro' => 'Não foi possível salvar a imagem no servidor.', 'nomeArquivo' => null];
    }

    return ['erro' => null, 'nomeArquivo' => CAMINHO_UPLOAD_WEB . $nomeUnico];
}


function removerImagemAntiga(?string $caminhoRelativo): void
{
    if (!$caminhoRelativo) {
        return;
    }


    if (!str_starts_with($caminhoRelativo, CAMINHO_UPLOAD_WEB)) {
        return;
    }

    $caminhoFisico = __DIR__ . '/../' . $caminhoRelativo;
    if (is_file($caminhoFisico)) {
        @unlink($caminhoFisico);
    }
}


function formatarNumeroCompacto(int $numero): string
{
    if ($numero >= 1_000_000) {
        return number_format($numero / 1_000_000, 1, ',', '.') . 'M';
    }
    if ($numero >= 1_000) {
        return number_format($numero / 1_000, 1, ',', '.') . 'mil';
    }
    return (string) $numero;
}
