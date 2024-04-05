-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           10.4.32-MariaDB - mariadb.org binary distribution
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para db_coffee_break
CREATE DATABASE IF NOT EXISTS `db_coffee_break` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `db_coffee_break`;

-- Copiando estrutura para tabela db_coffee_break.coffee_type
CREATE TABLE IF NOT EXISTS `coffee_type` (
  `id_coffee` int(11) unsigned NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  KEY `id_coffee` (`id_coffee`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela db_coffee_break.coffee_type: ~10 rows (aproximadamente)
INSERT INTO `coffee_type` (`id_coffee`, `type`) VALUES
	(2, 'Cappuccino'),
	(1, 'Espresso'),
	(3, 'Latte'),
	(4, 'Americano'),
	(5, 'Mocha'),
	(6, 'Macchiato'),
	(7, 'Flat White'),
	(8, 'Affogato'),
	(9, 'Irish Coffee'),
	(10, 'Turkish Coffee');

-- Copiando estrutura para tabela db_coffee_break.coffee_users
CREATE TABLE IF NOT EXISTS `coffee_users` (
  `iduser` int(11) unsigned NOT NULL,
  `id_coffee` int(11) unsigned NOT NULL,
  `coffee_qty` int(11) unsigned NOT NULL DEFAULT 1,
  `log_date` date NOT NULL,
  KEY `id_coffee` (`id_coffee`),
  KEY `id_user` (`iduser`) USING BTREE,
  CONSTRAINT `fk_users` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela db_coffee_break.coffee_users: ~0 rows (aproximadamente)

-- Copiando estrutura para tabela db_coffee_break.users
CREATE TABLE IF NOT EXISTS `users` (
  `iduser` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `token` varchar(33) NOT NULL,
  PRIMARY KEY (`email`) USING BTREE,
  UNIQUE KEY `id` (`iduser`) USING BTREE,
  KEY `token` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Copiando dados para a tabela db_coffee_break.users: ~0 rows (aproximadamente)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
