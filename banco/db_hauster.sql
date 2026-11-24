CREATE DATABASE IF NOT EXISTS `db_hauster`;
USE `db_hauster`;

CREATE TABLE IF NOT EXISTS planos (
  idplanos int(11) NOT NULL AUTO_INCREMENT,
  nome varchar(45) NOT NULL,  
  preco decimal(10,2) NOT NULL,
  PRIMARY KEY (idplanos)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;            

INSERT IGNORE INTO planos (idplanos, nome, preco) VALUES
  (1, 'Iniciante', 29.99), (2, 'Intermediário', 49.99), (3, 'Avançado', 79.99);

CREATE TABLE IF NOT EXISTS `estabelecimento` (
  `idestabelecimento` int(11) NOT NULL AUTO_INCREMENT,
  `nome_fantasia` varchar(45) NOT NULL,
  `cnpj` varchar(14) NOT NULL DEFAULT '',
  `email` varchar(45) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `planos_idplanos` INT NOT NULL,
  PRIMARY KEY (`idestabelecimento`),
  UNIQUE KEY `cnpj_UNIQUE` (`cnpj`),
  CONSTRAINT fk_estabelecimento_planos1 FOREIGN KEY (planos_idplanos) REFERENCES db_hauster.planos (idplanos) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT IGNORE INTO `estabelecimento` (`idestabelecimento`, `nome_fantasia`, `cnpj`, `email`, `senha`, `foto`, `planos_idplanos`) VALUES
	(18, 'Petz', '76919591000167', 'petz@gmail.com', '$2y$10$FFDccoM0atyLvniH3kdEAew/ST9zrpc.jBao9/.LqWG4eaGXjZCA2', '../assets/foto_padrao.png', '3');

CREATE TABLE IF NOT EXISTS `cargos` (
  `idcargos` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`idcargos`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT IGNORE INTO `cargos` (`idcargos`, `nome`) VALUES
	(1, 'Vendedor'),
	(2, 'Atendente'),
	(3, 'Veterinário'),
	(4, 'Tosador'),
	(5, 'Gerente'),
	(6, 'Outros');

CREATE TABLE IF NOT EXISTS `categorias` (
  `idcategorias` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  PRIMARY KEY (`idcategorias`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT IGNORE INTO `categorias` (`idcategorias`, `nome`) VALUES
	(1, 'Ração'),
	(2, 'Brinquedo'),
	(3, 'Utensilios'),
	(4, 'Remédios'),
	(5, 'Higiene'),
	(6, 'Outros');

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

CREATE TABLE IF NOT EXISTS `funcionarios` (
  `idfuncionarios` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `dt_nasc` date NOT NULL,
  `salario` decimal(10,2) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `email` varchar(45) NOT NULL,
  `telefone` varchar(14) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cargos_idcargos` int(11) NOT NULL,
  `estabelecimento_idestabelecimento` int(11) NOT NULL,
  PRIMARY KEY (`idfuncionarios`),
  UNIQUE KEY `uk_funcionarios_id_cargo` (`idfuncionarios`,`cargos_idcargos`),
  KEY `fk_funcionarios_cargos_id` (`cargos_idcargos`),
  KEY `fk_funcionarios_estabelecimento_id` (`estabelecimento_idestabelecimento`),
  CONSTRAINT `fk_funcionarios_cargos` FOREIGN KEY (`cargos_idcargos`) REFERENCES `cargos` (`idcargos`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_funcionarios_estabelecimento` FOREIGN KEY (`estabelecimento_idestabelecimento`) REFERENCES `estabelecimento` (`idestabelecimento`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT IGNORE INTO `funcionarios` (`idfuncionarios`, `nome`, `dt_nasc`, `salario`, `cpf`, `email`, `telefone`, `senha`, `cargos_idcargos`, `estabelecimento_idestabelecimento`) VALUES
	(1, 'Gustavo', '2007-05-25', 2500.00, '19797682773', 'gustavo@gmail.com', '21971291279', '$2y$10$1Q53GIman.ZnGU7M7JnFCOagXaDCJgbuIlBrpJxVBeMnWGvtxjr7O', 1, 18);

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

INSERT IGNORE INTO `produtos` (`idprodutos`, `nome_prod`, `validade`, `estoque`, `marca`, `preco`, `categorias_idcategorias`, `estabelecimento_idestabelecimento`) VALUES
	(26, 'Ração seca', '2027-05-25', 26, 'Golden', 150.00, 1, 18),
	(27, 'Perfume Pet', '2027-01-30', 3, 'Pet Society', 30.00, 5, 18),
	(28, 'Spray Antisséptico', '2026-12-08', 12, 'Ibasa', 29.90, 4, 18),
	(29, 'Caixa de Areia', '2030-05-18', 32, 'Furba', 40.00, 3, 18);

CREATE TABLE IF NOT EXISTS `itens_pedido` (
  `id_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `produtos_idprodutos` int(11) NOT NULL,
  `dt_pedido` date NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  `form_pagamento` enum('Crédito','Débito','Pix','Dinheiro') NOT NULL,
  `estabelecimento_idestabelecimento` int(11) NOT NULL,
  `funcionarios_idfuncionarios` int(11),
  PRIMARY KEY (`id_pedido`),
  KEY `fk_itens_pedido_estabelecimento1_idx` (`estabelecimento_idestabelecimento`),
  KEY `fk_itens_pedido_funcionarios1_idx` (`funcionarios_idfuncionarios`),
  CONSTRAINT `fk_itens_pedido_funcionarios1` FOREIGN KEY (`funcionarios_idfuncionarios`) REFERENCES `funcionarios` (`idfuncionarios`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT IGNORE INTO `itens_pedido` (`id_pedido`, `produtos_idprodutos`, `dt_pedido`, `valor`, `quantidade`, `form_pagamento`, `estabelecimento_idestabelecimento`, `funcionarios_idfuncionarios`) VALUES
	(24, 29, '2026-10-14', 80.00, 2, 'Crédito', 18, 1),
	(25, 27, '2026-10-14', 150.00, 5, 'Débito', 18, 1),
	(26, 26, '2026-10-14', 1350.00, 9, 'Dinheiro', 18, 1);
    
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
