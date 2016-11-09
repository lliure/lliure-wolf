CREATE TABLE IF NOT EXISTS `ll_lliure_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(200) NULL,
  `senha` varchar(200) NULL,
  `nome` varchar(200) NOT NULL,
  `email` varchar(256) DEFAULT NULL,
  `twitter` varchar(50) DEFAULT NULL,
  `foto` varchar(256) DEFAULT NULL,
  `grupo` varchar(10) NOT NULL DEFAULT 'user',
  `themer` varchar(50) DEFAULT 'default',
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `ll_lliure_autenticacao` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`login` VARCHAR(200) NOT NULL,
	`nome` VARCHAR(200) NOT NULL,
	`grupo` VARCHAR(10) NULL DEFAULT 'user',
	`tema` VARCHAR(50) NULL DEFAULT 'default',
	`ultimoacesso` BIGINT(20) NOT NULL,
	`cadastro` BIGINT(20) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `login` (`login`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `ll_lliure_desktop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `link` varchar(200) NOT NULL,
  `imagem` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `ll_lliure_apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  `pasta` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pasta` (`pasta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `ll_lliure_liberacao` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`operation_type` VARCHAR(255) NOT NULL,
	`operation_load` VARCHAR(255) NOT NULL,
	`login` VARCHAR(255) NOT NULL,
	`hash` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `operation_type_operation_load_login` (`operation_type`, `operation_load`, `login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;