-- =============================================================
-- SAEP - Controle de reposição de medicamentos
-- Script de criação do banco de dados (MySQL / MariaDB)
-- =============================================================

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS saep_farmacia;
CREATE DATABASE saep_farmacia
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE saep_farmacia;

-- -------------------------------------------------------------
-- Tabela: funcionario
-- -------------------------------------------------------------
CREATE TABLE funcionario (
    id_funcionario INT          NOT NULL AUTO_INCREMENT,
    nome           VARCHAR(100) NOT NULL,
    email          VARCHAR(150) NOT NULL,
    CONSTRAINT pk_funcionario PRIMARY KEY (id_funcionario),
    CONSTRAINT uk_funcionario_email UNIQUE (email)
) ENGINE = InnoDB;

-- -------------------------------------------------------------
-- Tabela: pedido_reposicao
-- 1 funcionário : N pedidos
-- -------------------------------------------------------------
CREATE TABLE pedido_reposicao (
    id_pedido        INT          NOT NULL AUTO_INCREMENT,
    id_funcionario   INT          NOT NULL,
    medicamento      VARCHAR(100) NOT NULL,
    quantidade       INT          NOT NULL,
    categoria        VARCHAR(20)  NOT NULL,
    urgencia         ENUM('baixa', 'media', 'alta') NOT NULL,
    data_solicitacao DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status           ENUM('solicitado', 'em_separacao', 'recebido') NOT NULL DEFAULT 'solicitado',
    CONSTRAINT pk_pedido_reposicao PRIMARY KEY (id_pedido),
    CONSTRAINT fk_pedido_funcionario FOREIGN KEY (id_funcionario)
        REFERENCES funcionario (id_funcionario)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT ck_pedido_quantidade CHECK (quantidade > 0)
) ENGINE = InnoDB;

-- -------------------------------------------------------------
-- Dados de teste
-- -------------------------------------------------------------
INSERT INTO funcionario (nome, email) VALUES
    ('Ana Souza',      'ana.souza@farmacia.com'),
    ('Bruno Oliveira', 'bruno.oliveira@farmacia.com'),
    ('Carla Mendes',   'carla.mendes@farmacia.com');

INSERT INTO pedido_reposicao (id_funcionario, medicamento, quantidade, categoria, urgencia, data_solicitacao, status) VALUES
    (1, 'Dipirona 500mg',        50, 'generico',   'alta',  '2026-09-20 08:30:00', 'solicitado'),
    (2, 'Amoxicilina 500mg',     20, 'referencia', 'media', '2026-09-21 10:15:00', 'solicitado'),
    (3, 'Clonazepam 2mg',        10, 'controlado', 'alta',  '2026-09-22 14:00:00', 'em_separacao'),
    (1, 'Sabonete antisséptico', 30, 'higiene',    'baixa', '2026-09-23 09:45:00', 'recebido'),
    (2, 'Losartana 50mg',        40, 'generico',   'media', '2026-09-24 16:20:00', 'em_separacao'),
    (3, 'Omeprazol 20mg',        25, 'generico',   'baixa', '2026-09-25 11:00:00', 'solicitado');
