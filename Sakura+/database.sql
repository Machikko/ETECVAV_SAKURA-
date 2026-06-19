

CREATE DATABASE IF NOT EXISTS animevault_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE animevault_db;


CREATE TABLE IF NOT EXISTS animes (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nome            VARCHAR(150)        NOT NULL,
    genero          VARCHAR(100)        NOT NULL,
    ano_lancamento  SMALLINT UNSIGNED   NOT NULL,
    visualizacoes   BIGINT UNSIGNED     NOT NULL DEFAULT 0,
    likes           BIGINT UNSIGNED     NOT NULL DEFAULT 0,
    imagem          VARCHAR(255)        NOT NULL,
    criado_em       TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em   TIMESTAMP           NOT NULL DEFAULT CURRENT_TIMESTAMP
                                         ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_animes_nome (nome),
    INDEX idx_animes_genero (genero)
);


