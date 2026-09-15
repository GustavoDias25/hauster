-- -----------------------------------------------------
-- Schema db_hauster
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema db_hauster
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `db_hauster` DEFAULT CHARACTER SET utf8 ;
USE `db_hauster` ;

-- -----------------------------------------------------
-- Table `db_hauster`.`estabelecimento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_hauster`.`estabelecimento` (
  `idestabelecimento` INT NOT NULL AUTO_INCREMENT,
  `nome_fantasia` VARCHAR(45) NOT NULL,
  `cnpj` INT UNSIGNED NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `senha` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`idestabelecimento`),
  UNIQUE INDEX `cnpj_UNIQUE` (`cnpj` ASC))
  `foto` VARCHAR(255) NOT NULL
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_hauster`.`clientes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_hauster`.`clientes` (
  `idclientes` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `telefone` VARCHAR(14) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `cpf` VARCHAR(11) NOT NULL,
  `estabelecimento_idestabelecimento` INT NOT NULL,
  PRIMARY KEY (`idclientes`, `estabelecimento_idestabelecimento`),
  INDEX `fk_clientes_estabelecimento1_idx` (`estabelecimento_idestabelecimento` ASC),
  CONSTRAINT `fk_clientes_estabelecimento1`
    FOREIGN KEY (`estabelecimento_idestabelecimento`)
    REFERENCES `db_hauster`.`estabelecimento` (`idestabelecimento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_hauster`.`cargos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_hauster`.`cargos` (
  `idcargos` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idcargos`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_hauster`.`funcionarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_hauster`.`funcionarios` (
  `idfuncionarios` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `dt_nasc` DATE NOT NULL,
  `salario` DECIMAL(10,2) NOT NULL,
  `tipo` VARCHAR(45) NOT NULL,
  `cpf` INT NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `telefone` INT NOT NULL,
  `cargos_idcargos` INT NOT NULL,
  `estabelecimento_idestabelecimento` INT NOT NULL,
  PRIMARY KEY (`idfuncionarios`, `cargos_idcargos`, `estabelecimento_idestabelecimento`),
  INDEX `fk_funcionarios_cargos1_idx` (`cargos_idcargos` ASC),
  INDEX `fk_funcionarios_estabelecimento1_idx` (`estabelecimento_idestabelecimento` ASC),
  CONSTRAINT `fk_funcionarios_cargos1`
    FOREIGN KEY (`cargos_idcargos`)
    REFERENCES `db_hauster`.`cargos` (`idcargos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_funcionarios_estabelecimento1`
    FOREIGN KEY (`estabelecimento_idestabelecimento`)
    REFERENCES `db_hauster`.`estabelecimento` (`idestabelecimento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_hauster`.`categorias`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_hauster`.`categorias` (
  `idcategorias` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idcategorias`))
ENGINE = InnoDB;

INSERT INTO `categorias` (`idcategorias`, `nome`) VALUES
	(1, 'ração'),
	(2, 'brinquedo'),
	(3, 'utensilios'),
	(4, 'remédios'),
	(5, 'outros');


-- -----------------------------------------------------
-- Table `db_hauster`.`produtos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_hauster`.`produtos` (
  `idprodutos` INT NOT NULL AUTO_INCREMENT,
  `nome_prod` VARCHAR(45) NOT NULL,
  `validade` DATE NOT NULL,
  `estoque` INT NOT NULL,
  `estoque_min` INT NULL,
  `marca` VARCHAR(45) NOT NULL,
  `preco` DECIMAL(10,2) NOT NULL,
  `categorias_idcategorias` INT NOT NULL,
  `estabelecimento_idestabelecimento` INT NOT NULL,
  PRIMARY KEY (`idprodutos`, `categorias_idcategorias`, `estabelecimento_idestabelecimento`),
  INDEX `fk_produtos_categorias1_idx` (`categorias_idcategorias` ASC),
  INDEX `fk_produtos_estabelecimento1_idx` (`estabelecimento_idestabelecimento` ASC),
  CONSTRAINT `fk_produtos_categorias1`
    FOREIGN KEY (`categorias_idcategorias`)
    REFERENCES `db_hauster`.`categorias` (`idcategorias`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_produtos_estabelecimento1`
    FOREIGN KEY (`estabelecimento_idestabelecimento`)
    REFERENCES `db_hauster`.`estabelecimento` (`idestabelecimento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_hauster`.`pets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_hauster`.`pets` (
  `idpets` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `idade` INT NOT NULL,
  `especie` VARCHAR(45) NOT NULL,
  `raca` VARCHAR(45) NOT NULL,
  `sexo` ENUM("macho", "femea") NOT NULL,
  `clientes_idclientes` INT NOT NULL,
  `estabelecimento_idestabelecimento` INT NOT NULL,
  PRIMARY KEY (`idpets`, `clientes_idclientes`, `estabelecimento_idestabelecimento`),
  INDEX `fk_pets_clientes1_idx` (`clientes_idclientes` ASC),
  INDEX `fk_pets_estabelecimento1_idx` (`estabelecimento_idestabelecimento` ASC),
  CONSTRAINT `fk_pets_clientes1`
    FOREIGN KEY (`clientes_idclientes`)
    REFERENCES `db_hauster`.`clientes` (`idclientes`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_pets_estabelecimento1`
    FOREIGN KEY (`estabelecimento_idestabelecimento`)
    REFERENCES `db_hauster`.`estabelecimento` (`idestabelecimento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_hauster`.`servicos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_hauster`.`servicos` (
  `idservicos` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `tempo_estimado` TIME NOT NULL,
  `preco` DECIMAL(10,2) NOT NULL,
  `funcionarios_idfuncionarios` INT NOT NULL,
  `funcionarios_cargos_idcargos` INT NOT NULL,
  `estabelecimento_idestabelecimento` INT NOT NULL,
  PRIMARY KEY (`idservicos`, `funcionarios_idfuncionarios`, `funcionarios_cargos_idcargos`, `estabelecimento_idestabelecimento`),
  INDEX `fk_servicos_funcionarios1_idx` (`funcionarios_idfuncionarios` ASC, `funcionarios_cargos_idcargos` ASC),
  INDEX `fk_servicos_estabelecimento1_idx` (`estabelecimento_idestabelecimento` ASC),
  CONSTRAINT `fk_servicos_funcionarios1`
    FOREIGN KEY (`funcionarios_idfuncionarios` , `funcionarios_cargos_idcargos`)
    REFERENCES `db_hauster`.`funcionarios` (`idfuncionarios` , `cargos_idcargos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_servicos_estabelecimento1`
    FOREIGN KEY (`estabelecimento_idestabelecimento`)
    REFERENCES `db_hauster`.`estabelecimento` (`idestabelecimento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_hauster`.`itens_pedido`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_hauster`.`itens_pedido` (
  `produtos_idprodutos` INT NOT NULL AUTO_INCREMENT,
  `produtos_categorias_idcategorias` INT NOT NULL,
  `clientes_idclientes` INT NOT NULL,
  `dt_pedido` DATE NOT NULL,
  `valor` DECIMAL(10,2) NOT NULL,
  `form_pagamento` ENUM("credito", "debito", "pix", "dinheiro") NOT NULL,
  `estabelecimento_idestabelecimento` INT NOT NULL,
  PRIMARY KEY (`produtos_idprodutos`, `produtos_categorias_idcategorias`, `clientes_idclientes`, `estabelecimento_idestabelecimento`),
  INDEX `fk_produtos_has_clientes_clientes1_idx` (`clientes_idclientes` ASC),
  INDEX `fk_produtos_has_clientes_produtos1_idx` (`produtos_idprodutos` ASC, `produtos_categorias_idcategorias` ASC),
  INDEX `fk_itens_pedido_estabelecimento1_idx` (`estabelecimento_idestabelecimento` ASC),
  CONSTRAINT `fk_produtos_has_clientes_produtos1`
    FOREIGN KEY (`produtos_idprodutos` , `produtos_categorias_idcategorias`)
    REFERENCES `db_hauster`.`produtos` (`idprodutos` , `categorias_idcategorias`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_produtos_has_clientes_clientes1`
    FOREIGN KEY (`clientes_idclientes`)
    REFERENCES `db_hauster`.`clientes` (`idclientes`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_itens_pedido_estabelecimento1`
    FOREIGN KEY (`estabelecimento_idestabelecimento`)
    REFERENCES `db_hauster`.`estabelecimento` (`idestabelecimento`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `db_hauster`.`clientes_has_servicos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `db_hauster`.`clientes_has_servicos` (
  `clientes_idclientes` INT NOT NULL AUTO_INCREMENT,
  `servicos_idservicos` INT NOT NULL,
  `servicos_funcionarios_idfuncionarios` INT NOT NULL,
  `servicos_funcionarios_cargos_idcargos` INT NOT NULL,
  `dt_servico` DATE NOT NULL,
  `valor` DECIMAL(10,2) NOT NULL,
  `form_pagamente` ENUM("credito", "debito", "pix", "dinheiro") NOT NULL,
  PRIMARY KEY (`clientes_idclientes`, `servicos_idservicos`, `servicos_funcionarios_idfuncionarios`, `servicos_funcionarios_cargos_idcargos`),
  INDEX `fk_clientes_has_servicos_servicos1_idx` (`servicos_idservicos` ASC, `servicos_funcionarios_idfuncionarios` ASC, `servicos_funcionarios_cargos_idcargos` ASC),
  INDEX `fk_clientes_has_servicos_clientes1_idx` (`clientes_idclientes` ASC),
  CONSTRAINT `fk_clientes_has_servicos_clientes1`
    FOREIGN KEY (`clientes_idclientes`)
    REFERENCES `db_hauster`.`clientes` (`idclientes`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_clientes_has_servicos_servicos1`
    FOREIGN KEY (`servicos_idservicos` , `servicos_funcionarios_idfuncionarios` , `servicos_funcionarios_cargos_idcargos`)
    REFERENCES `db_hauster`.`servicos` (`idservicos` , `funcionarios_idfuncionarios` , `funcionarios_cargos_idcargos`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;