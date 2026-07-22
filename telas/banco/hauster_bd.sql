-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.16.0.7229
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para hauster_bd
CREATE DATABASE IF NOT EXISTS `hauster_bd` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci */;
USE `hauster_bd`;

-- Copiando estrutura para tabela hauster_bd.cargos
CREATE TABLE IF NOT EXISTS `cargos` (
  `idcargos` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`idcargos`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela hauster_bd.categorias
CREATE TABLE IF NOT EXISTS `categorias` (
  `idcategorias` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`idcategorias`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela hauster_bd.clientes
CREATE TABLE IF NOT EXISTS `clientes` (
  `idclientes` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `telefone` varchar(14) NOT NULL,
  `email` varchar(45) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  PRIMARY KEY (`idclientes`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela hauster_bd.clientes_has_servicos
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

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela hauster_bd.estabelecimento
CREATE TABLE IF NOT EXISTS `estabelecimento` (
  `idestabelecimento` int(11) NOT NULL AUTO_INCREMENT,
  `nome_fantasia` varchar(45) NOT NULL,
  `cnpj` int(10) unsigned NOT NULL,
  `endereco` varchar(45) NOT NULL,
  `produtos_idprodutos` int(11) NOT NULL,
  `produtos_categorias_idcategorias` int(11) NOT NULL,
  `funcionarios_idfuncionarios` int(11) NOT NULL,
  `funcionarios_cargos_idcargos` int(11) NOT NULL,
  PRIMARY KEY (`idestabelecimento`,`produtos_idprodutos`,`produtos_categorias_idcategorias`,`funcionarios_idfuncionarios`,`funcionarios_cargos_idcargos`),
  KEY `fk_estabelecimento_produtos1_idx` (`produtos_idprodutos`,`produtos_categorias_idcategorias`),
  KEY `fk_estabelecimento_funcionarios1_idx` (`funcionarios_idfuncionarios`,`funcionarios_cargos_idcargos`),
  CONSTRAINT `fk_estabelecimento_funcionarios1` FOREIGN KEY (`funcionarios_idfuncionarios`, `funcionarios_cargos_idcargos`) REFERENCES `funcionarios` (`idfuncionarios`, `cargos_idcargos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_estabelecimento_produtos1` FOREIGN KEY (`produtos_idprodutos`, `produtos_categorias_idcategorias`) REFERENCES `produtos` (`idprodutos`, `categorias_idcategorias`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela hauster_bd.funcionarios
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
  PRIMARY KEY (`idfuncionarios`,`cargos_idcargos`),
  KEY `fk_funcionarios_cargos1_idx` (`cargos_idcargos`),
  CONSTRAINT `fk_funcionarios_cargos1` FOREIGN KEY (`cargos_idcargos`) REFERENCES `cargos` (`idcargos`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela hauster_bd.itens_pedido
CREATE TABLE IF NOT EXISTS `itens_pedido` (
  `produtos_idprodutos` int(11) NOT NULL AUTO_INCREMENT,
  `produtos_categorias_idcategorias` int(11) NOT NULL,
  `clientes_idclientes` int(11) NOT NULL,
  `dt_pedido` date NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `form_pagamento` enum('credito','debito','pix','dinheiro') NOT NULL,
  PRIMARY KEY (`produtos_idprodutos`,`produtos_categorias_idcategorias`,`clientes_idclientes`),
  KEY `fk_produtos_has_clientes_clientes1_idx` (`clientes_idclientes`),
  KEY `fk_produtos_has_clientes_produtos1_idx` (`produtos_idprodutos`,`produtos_categorias_idcategorias`),
  CONSTRAINT `fk_produtos_has_clientes_clientes1` FOREIGN KEY (`clientes_idclientes`) REFERENCES `clientes` (`idclientes`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_produtos_has_clientes_produtos1` FOREIGN KEY (`produtos_idprodutos`, `produtos_categorias_idcategorias`) REFERENCES `produtos` (`idprodutos`, `categorias_idcategorias`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela hauster_bd.pets
CREATE TABLE IF NOT EXISTS `pets` (
  `idpets` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `idade` int(11) NOT NULL,
  `especie` varchar(45) NOT NULL,
  `raca` varchar(45) NOT NULL,
  `sexo` enum('macho','femea') NOT NULL,
  `clientes_idclientes` int(11) NOT NULL,
  PRIMARY KEY (`idpets`,`clientes_idclientes`),
  KEY `fk_pets_clientes1_idx` (`clientes_idclientes`),
  CONSTRAINT `fk_pets_clientes1` FOREIGN KEY (`clientes_idclientes`) REFERENCES `clientes` (`idclientes`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela hauster_bd.produtos
CREATE TABLE IF NOT EXISTS `produtos` (
  `idprodutos` int(11) NOT NULL AUTO_INCREMENT,
  `nome_prod` varchar(45) NOT NULL,
  `validade` date NOT NULL,
  `estoque` int(11) NOT NULL,
  `estoque_min` int(11) DEFAULT NULL,
  `marca` varchar(45) NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `categorias_idcategorias` int(11) NOT NULL,
  PRIMARY KEY (`idprodutos`,`categorias_idcategorias`),
  KEY `fk_produtos_categorias1_idx` (`categorias_idcategorias`),
  CONSTRAINT `fk_produtos_categorias1` FOREIGN KEY (`categorias_idcategorias`) REFERENCES `categorias` (`idcategorias`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela hauster_bd.servicos
CREATE TABLE IF NOT EXISTS `servicos` (
  `idservicos` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `tempo_estimado` time NOT NULL,
  `preco` decimal(10,2) NOT NULL,
  `funcionarios_idfuncionarios` int(11) NOT NULL,
  `funcionarios_cargos_idcargos` int(11) NOT NULL,
  PRIMARY KEY (`idservicos`,`funcionarios_idfuncionarios`,`funcionarios_cargos_idcargos`),
  KEY `fk_servicos_funcionarios1_idx` (`funcionarios_idfuncionarios`,`funcionarios_cargos_idcargos`),
  CONSTRAINT `fk_servicos_funcionarios1` FOREIGN KEY (`funcionarios_idfuncionarios`, `funcionarios_cargos_idcargos`) REFERENCES `funcionarios` (`idfuncionarios`, `cargos_idcargos`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Exportação de dados foi desmarcado.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
