-- Adminer 4.8.1 MySQL 5.5.5-10.5.12-MariaDB-cll-lve dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `capitulos`;
CREATE TABLE `capitulos` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capitulo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `historia_id` bigint(20) NOT NULL,
  `caminho_capa` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantidade_visualizacao` bigint(20) NOT NULL,
  `votacao` bigint(20) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `historia_id` (`historia_id`),
  CONSTRAINT `capitulos_ibfk_1` FOREIGN KEY (`historia_id`) REFERENCES `historia` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `categoria`;
CREATE TABLE `categoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `genero` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `comentarios`;
CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `capitulo_id` bigint(20) NOT NULL,
  `comentario` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario_id` bigint(20) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `capitulo_id` (`capitulo_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`capitulo_id`) REFERENCES `capitulos` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `direitos_autorais`;
CREATE TABLE `direitos_autorais` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_autoral` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `historia`;
CREATE TABLE `historia` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `publico_alvo_id` int(11) NOT NULL,
  `idioma_id` int(11) NOT NULL,
  `direitos_autorais_id` int(11) NOT NULL,
  `conteudo_adulto` bit(1) NOT NULL,
  `caminho_capa` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `usuario_id` bigint(20) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `categoria_id` (`categoria_id`),
  KEY `idioma_id` (`idioma_id`),
  KEY `publico_alvo_id` (`publico_alvo_id`),
  KEY `direitos_autorais_id` (`direitos_autorais_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `historia_ibfk_4` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON UPDATE NO ACTION,
  CONSTRAINT `historia_ibfk_5` FOREIGN KEY (`idioma_id`) REFERENCES `idioma` (`id`) ON UPDATE NO ACTION,
  CONSTRAINT `historia_ibfk_6` FOREIGN KEY (`publico_alvo_id`) REFERENCES `publico_alvo` (`id`) ON UPDATE NO ACTION,
  CONSTRAINT `historia_ibfk_7` FOREIGN KEY (`direitos_autorais_id`) REFERENCES `direitos_autorais` (`id`) ON UPDATE NO ACTION,
  CONSTRAINT `historia_ibfk_8` FOREIGN KEY (`usuario_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
