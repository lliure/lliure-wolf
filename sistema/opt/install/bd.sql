-- --------------------------------------------------------
-- Servidor:                     192.168.0.10
-- Versão do servidor:           5.5.31 - MySQL Community Server (GPL)
-- OS do Servidor:               Linux
-- HeidiSQL Versão:              7.0.0.4389
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando estrutura para tabela ll_lliure_admin
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela ll_lliure_admin: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `ll_lliure_admin` DISABLE KEYS */;
INSERT INTO `ll_lliure_admin` (`id`, `login`, `senha`, `nome`, `email`, `twitter`, `foto`, `grupo`, `themer`) VALUES
	(1, 'dev', '0beb32fac408c864385802e9be24ee4c', 'Desenvolvedor', NULL, NULL, NULL, 'dev', 'default');
/*!40000 ALTER TABLE `ll_lliure_admin` ENABLE KEYS */;


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
ENGINE=InnoDB
AUTO_INCREMENT=1000000001
;

-- Copiando estrutura para tabela ll_lliure_desktop
CREATE TABLE IF NOT EXISTS `ll_lliure_desktop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `link` varchar(200) NOT NULL,
  `imagem` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Copiando dados para a tabela ll_lliure_desktop: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `ll_lliure_desktop` DISABLE KEYS */;
/*!40000 ALTER TABLE `ll_lliure_desktop` ENABLE KEYS */;


-- Copiando estrutura para tabela ll_lliure_apps
CREATE TABLE IF NOT EXISTS `ll_lliure_apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  `pasta` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pasta` (`pasta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

