-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           5.5.27 - MySQL Community Server (GPL)
-- OS do Servidor:               Win32
-- HeidiSQL Versão:              8.3.0.4694
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Copiando estrutura para tabela agostinho.ll_newsmade_albuns
CREATE TABLE IF NOT EXISTS `ll_newsmade_albuns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(256) NOT NULL,
  `capa` int(11) DEFAULT NULL,
  `data` char(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela agostinho.ll_newsmade_albuns_fotos
CREATE TABLE IF NOT EXISTS `ll_newsmade_albuns_fotos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album` int(11) NOT NULL,
  `foto` varchar(256) DEFAULT NULL,
  `descricao` varchar(256) DEFAULT NULL,
  `ordem` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `album` (`album`),
  KEY `album_2` (`album`),
  CONSTRAINT `ll_newsmade_albuns_fotos_ibfk_1` FOREIGN KEY (`album`) REFERENCES `ll_newsmade_albuns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela agostinho.ll_newsmade_albuns_videos
CREATE TABLE IF NOT EXISTS `ll_newsmade_albuns_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `album` int(11) NOT NULL,
  `video` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `album` (`album`),
  CONSTRAINT `ll_newsmade_albuns_videos_ibfk_1` FOREIGN KEY (`album`) REFERENCES `ll_newsmade_albuns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela agostinho.ll_newsmade_blogs
CREATE TABLE IF NOT EXISTS `ll_newsmade_blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `url` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela agostinho.ll_newsmade_postagens
CREATE TABLE IF NOT EXISTS `ll_newsmade_postagens` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`modo` ENUM('0','1') NOT NULL DEFAULT '0',
	`blog` INT(11) NULL DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `titulo` VARCHAR(256) NULL DEFAULT NULL,
  `subtitulo` varchar(256) DEFAULT NULL,
  `introducao` text,
  `texto` text,
  `foto` int(11) DEFAULT NULL,
  `data` varchar(50) DEFAULT NULL,
  `data_up` varchar(50) DEFAULT NULL,
  `user` int(11) DEFAULT NULL,
  `publicar` enum('0','1') DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Index 2` (`blog`),
  CONSTRAINT `FK_ll_newsmade_postagens_ll_newsmade_categorias` FOREIGN KEY (`blog`) REFERENCES `ll_newsmade_blogs` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela agostinho.ll_newsmade_postagens_albuns
CREATE TABLE IF NOT EXISTS `ll_newsmade_postagens_albuns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPostagem` int(11) NOT NULL,
  `idAlbum` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idPostagem` (`idPostagem`,`idAlbum`),
  KEY `idAlbum` (`idAlbum`),
  CONSTRAINT `ll_newsmade_postagens_albuns_ibfk_1` FOREIGN KEY (`idPostagem`) REFERENCES `ll_newsmade_postagens` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ll_newsmade_postagens_albuns_ibfk_2` FOREIGN KEY (`idAlbum`) REFERENCES `ll_newsmade_albuns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela agostinho.ll_newsmade_postagens_comentarios
CREATE TABLE IF NOT EXISTS `ll_newsmade_postagens_comentarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postagem` int(11) NOT NULL,
  `titulo` varchar(256) NOT NULL,
  `comentario` text NOT NULL,
  `usuario` varchar(256) DEFAULT NULL,
  `email` varchar(256) NOT NULL,
  `status` enum('0','1') DEFAULT '0',
  `data` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `postagem` (`postagem`),
  CONSTRAINT `ll_newsmade_postagens_comentarios_ibfk_1` FOREIGN KEY (`postagem`) REFERENCES `ll_newsmade_postagens` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela agostinho.ll_newsmade_postagens_referencias
CREATE TABLE IF NOT EXISTS `ll_newsmade_postagens_referencias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idNoticia` int(11) NOT NULL,
  `link` varchar(256) DEFAULT NULL,
  `titulo` varchar(256) DEFAULT NULL,
  `mostitulo` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idNoticia` (`idNoticia`),
  CONSTRAINT `ll_newsmade_postagens_referencias_ibfk_1` FOREIGN KEY (`idNoticia`) REFERENCES `ll_newsmade_albuns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela agostinho.ll_newsmade_postagens_visualizacoes
CREATE TABLE IF NOT EXISTS `ll_newsmade_postagens_visualizacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postagem` int(11) NOT NULL,
  `data` bigint(14) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `postagem` (`postagem`),
  CONSTRAINT `ll_newsmade_postagens_visualizacoes_ibfk_1` FOREIGN KEY (`postagem`) REFERENCES `ll_newsmade_postagens` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.


-- Copiando estrutura para tabela agostinho.ll_newsmade_topicos
CREATE TABLE IF NOT EXISTS `ll_newsmade_topicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topico` varchar(70) NOT NULL,
  `postagem` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `postagem` (`postagem`),
  CONSTRAINT `ll_newsmade_topicos_ibfk_1` FOREIGN KEY (`postagem`) REFERENCES `ll_newsmade_postagens` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
