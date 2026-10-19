-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.21.0.7344
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para db_hauster
CREATE DATABASE IF NOT EXISTS `db_hauster` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci */;
USE `db_hauster`;

-- Copiando estrutura para tabela db_hauster.cargos
CREATE TABLE IF NOT EXISTS `cargos` (
  `idcargos` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`idcargos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Copiando dados para a tabela db_hauster.cargos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela db_hauster.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `idcategorias` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`idcategorias`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Copiando dados para a tabela db_hauster.categorias: ~6 rows (aproximadamente)
INSERT IGNORE INTO `categorias` (`idcategorias`, `nome`) VALUES
	(1, 'Ração'),
	(2, 'Brinquedo'),
	(3, 'Utensilios'),
	(4, 'Remédios'),
	(5, 'Higiene'),
	(6, 'Outros');

-- Copiando estrutura para tabela db_hauster.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `idclientes` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `telefone` varchar(14) NOT NULL,
  `email` varchar(45) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `estabelecimento_idestabelecimento` int(11) NOT NULL,
  PRIMARY KEY (`idclientes`,`estabelecimento_idestabelecimento`),
  KEY `fk_clientes_estabelecimento1_idx` (`estabelecimento_idestabelecimento`),
  CONSTRAINT `fk_clientes_estabelecimento1` FOREIGN KEY (`estabelecimento_idestabelecimento`) REFERENCES `estabelecimento` (`idestabelecimento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Copiando dados para a tabela db_hauster.clientes: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela db_hauster.clientes_has_servicos
CREATE TABLE IF NOT EXISTS `clientes_has_servicos` (
  `clientes_idclientes` int(11) NOT NULL AUTO_INCREMENT,
  `servicos_idservicos` int(11) NOT NULL,
  `servicos_funcionarios_idfuncionarios` int(11) NOT NULL,
  `servicos_funcionarios_cargos_idcargos` int(11) NOT NULL,
  `dt_servico` date NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `form_pagamente` enum('credito','debito','pix','dinheiro') NOT NULL,
  PRIMARY KEY (`clientes_idclientes`,`servicos_idservicos`,`servicos_funcionarios_idfuncionarios`,`servicos_funcionarios_cargos_idcargos`),
  KEY `fk_clientes_has_servicos_servicos1_idx` (`servicos_idservicos`,`servicos_funcionarios_idfuncionarios`,`servicos_funcionarios_cargos_idcargos`),
  KEY `fk_clientes_has_servicos_clientes1_idx` (`clientes_idclientes`),
  CONSTRAINT `fk_clientes_has_servicos_clientes1` FOREIGN KEY (`clientes_idclientes`) REFERENCES `clientes` (`idclientes`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_clientes_has_servicos_servicos1` FOREIGN KEY (`servicos_idservicos`, `servicos_funcionarios_idfuncionarios`, `servicos_funcionarios_cargos_idcargos`) REFERENCES `servicos` (`idservicos`, `funcionarios_idfuncionarios`, `funcionarios_cargos_idcargos`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Copiando dados para a tabela db_hauster.clientes_has_servicos: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela db_hauster.estabelecimento
CREATE TABLE IF NOT EXISTS `estabelecimento` (
  `idestabelecimento` int(11) NOT NULL AUTO_INCREMENT,
  `nome_fantasia` varchar(45) NOT NULL,
  `cnpj` varchar(14) NOT NULL DEFAULT '',
  `email` varchar(45) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idestabelecimento`),
  UNIQUE KEY `cnpj_UNIQUE` (`cnpj`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Copiando dados para a tabela db_hauster.estabelecimento: ~0 rows (aproximadamente)
INSERT IGNORE INTO `estabelecimento` (`idestabelecimento`, `nome_fantasia`, `cnpj`, `email`, `senha`, `foto`) VALUES
	(18, 'Petz', '76919591000167', 'petz@gmail.com', '$2y$10$FFDccoM0atyLvniH3kdEAew/ST9zrpc.jBao9/.LqWG4eaGXjZCA2', '../assets/foto_padrao.png');

-- Copiando estrutura para tabela db_hauster.funcionarios
CREATE TABLE IF NOT EXISTS `funcionarios` (
  `idfuncionarios` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `dt_nasc` date NOT NULL,
  `salario` decimal(10,2) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `cpf` int(11) NOT NULL,
  `email` varchar(45) NOT NULL,
  `telefone` int(11) NOT NULL,
  `cargos_idcargos` int(11) NOT NULL,
  `estabelecimento_idestabelecimento` int(11) NOT NULL,
  PRIMARY KEY (`idfuncionarios`,`cargos_idcargos`,`estabelecimento_idestabelecimento`),
  KEY `fk_funcionarios_cargos1_idx` (`cargos_idcargos`),
  KEY `fk_funcionarios_estabelecimento1_idx` (`estabelecimento_idestabelecimento`),
  CONSTRAINT `fk_funcionarios_cargos1` FOREIGN KEY (`cargos_idcargos`) REFERENCES `cargos` (`idcargos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_funcionarios_estabelecimento1` FOREIGN KEY (`estabelecimento_idestabelecimento`) REFERENCES `estabelecimento` (`idestabelecimento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Copiando dados para a tabela db_hauster.funcionarios: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela db_hauster.itens_pedido
CREATE TABLE IF NOT EXISTS `itens_pedido` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `produtos_idprodutos` int(11) NOT NULL,
  `dt_pedido` date NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `form_pagamento` enum('Crédito','Débito','Pix','Dinheiro') NOT NULL,
  `estabelecimento_idestabelecimento` int(11) NOT NULL,
  PRIMARY KEY (`id_pedido`),
  KEY `fk_itens_pedido_estabelecimento1_idx` (`estabelecimento_idestabelecimento`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Copiando dados para a tabela db_hauster.itens_pedido: ~13 rows (aproximadamente)
INSERT IGNORE INTO `itens_pedido` (`id_pedido`, `produtos_idprodutos`, `dt_pedido`, `valor`, `quantidade`, `form_pagamento`, `estabelecimento_idestabelecimento`) VALUES
	(10, 10, '2026-10-07', 99.60, 4, 'Débito', 3),
	(11, 10, '2026-10-07', 49.80, 2, 'Pix', 3),
	(12, 6, '2026-10-07', 9000.00, 60, 'Crédito', 3),
	(13, 9, '2026-10-07', 13.50, 3, 'Dinheiro', 3),
	(14, 10, '2026-10-06', 124.50, 5, 'Crédito', 3),
	(15, 16, '2026-10-10', 99.60, 4, 'Crédito', 7),
	(16, 13, '2026-10-10', 3000.00, 20, 'Pix', 7),
	(17, 14, '2026-10-10', 149.50, 5, 'Débito', 7),
	(18, 16, '2026-10-10', 124.50, 5, 'Dinheiro', 7),
	(19, 18, '2026-10-12', 6.00, 5, 'Pix', 16),
	(20, 21, '2026-10-12', 700.00, 10, 'Crédito', 16),
	(22, 19, '2026-10-12', 99999999.99, 2, 'Crédito', 16),
	(23, 21, '2026-10-12', 2870.00, 41, 'Pix', 16),
	(24, 29, '2026-10-14', 80.00, 2, 'Crédito', 18),
	(25, 27, '2026-10-14', 150.00, 5, 'Débito', 18),
	(26, 26, '2026-10-14', 1350.00, 9, 'Dinheiro', 18);

-- Copiando estrutura para tabela db_hauster.pets
CREATE TABLE IF NOT EXISTS `pets` (
  `idpets` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `idade` int(11) NOT NULL,
  `especie` varchar(45) NOT NULL,
  `raca` varchar(45) NOT NULL,
  `sexo` enum('macho','femea') NOT NULL,
  `clientes_idclientes` int(11) NOT NULL,
  `estabelecimento_idestabelecimento` int(11) NOT NULL,
  PRIMARY KEY (`idpets`,`clientes_idclientes`,`estabelecimento_idestabelecimento`),
  KEY `fk_pets_clientes1_idx` (`clientes_idclientes`),
  KEY `fk_pets_estabelecimento1_idx` (`estabelecimento_idestabelecimento`),
  CONSTRAINT `fk_pets_clientes1` FOREIGN KEY (`clientes_idclientes`) REFERENCES `clientes` (`idclientes`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pets_estabelecimento1` FOREIGN KEY (`estabelecimento_idestabelecimento`) REFERENCES `estabelecimento` (`idestabelecimento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Copiando dados para a tabela db_hauster.pets: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela db_hauster.produtos
CREATE TABLE IF NOT EXISTS `produtos` (
  `idprodutos` int(11) NOT NULL AUTO_INCREMENT,
  `nome_prod` varchar(45) NOT NULL,
  `validade` date NOT NULL,
  `estoque` int(11) NOT NULL,
  `marca` varchar(45) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `categorias_idcategorias` int(11) NOT NULL,
  `estabelecimento_idestabelecimento` int(11) NOT NULL,
  PRIMARY KEY (`idprodutos`,`categorias_idcategorias`,`estabelecimento_idestabelecimento`),
  KEY `fk_produtos_categorias1_idx` (`categorias_idcategorias`),
  KEY `fk_produtos_estabelecimento1_idx` (`estabelecimento_idestabelecimento`),
  CONSTRAINT `fk_produtos_categorias1` FOREIGN KEY (`categorias_idcategorias`) REFERENCES `categorias` (`idcategorias`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_produtos_estabelecimento` FOREIGN KEY (`estabelecimento_idestabelecimento`) REFERENCES `estabelecimento` (`idestabelecimento`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Copiando dados para a tabela db_hauster.produtos: ~0 rows (aproximadamente)
INSERT IGNORE INTO `produtos` (`idprodutos`, `nome_prod`, `validade`, `estoque`, `marca`, `preco`, `categorias_idcategorias`, `estabelecimento_idestabelecimento`) VALUES
	(26, 'Ração seca', '2027-05-25', 26, 'Golden', 150.00, 1, 18),
	(27, 'Perfume Pet', '2027-01-30', 3, 'Pet Society', 30.00, 5, 18),
	(28, 'Spray Antisséptico', '2026-12-08', 12, 'Ibasa', 29.90, 4, 18),
	(29, 'Caixa de Areia', '2030-05-18', 32, 'Furba', 40.00, 3, 18);

-- Copiando estrutura para tabela db_hauster.servicos
CREATE TABLE IF NOT EXISTS `servicos` (
  `idservicos` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `tempo_estimado` time NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `funcionarios_idfuncionarios` int(11) NOT NULL,
  `funcionarios_cargos_idcargos` int(11) NOT NULL,
  `estabelecimento_idestabelecimento` int(11) NOT NULL,
  PRIMARY KEY (`idservicos`,`funcionarios_idfuncionarios`,`funcionarios_cargos_idcargos`,`estabelecimento_idestabelecimento`),
  KEY `fk_servicos_funcionarios1_idx` (`funcionarios_idfuncionarios`,`funcionarios_cargos_idcargos`),
  KEY `fk_servicos_estabelecimento1_idx` (`estabelecimento_idestabelecimento`),
  CONSTRAINT `fk_servicos_estabelecimento1` FOREIGN KEY (`estabelecimento_idestabelecimento`) REFERENCES `estabelecimento` (`idestabelecimento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_servicos_funcionarios1` FOREIGN KEY (`funcionarios_idfuncionarios`, `funcionarios_cargos_idcargos`) REFERENCES `funcionarios` (`idfuncionarios`, `cargos_idcargos`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Copiando dados para a tabela db_hauster.servicos: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
