# Database setup on creation of Docker database container.
#
# Note that database credentials are NOT secure and not designed to be - this Docker container
# should only be available locally and is not designed for use on any public or internal network.
GRANT ALL PRIVILEGES ON *.* to 'root'@'%' IDENTIFIED BY 'root';

DROP DATABASE IF EXISTS `restcountries`;
CREATE DATABASE `restcountries` COLLATE utf8mb4_unicode_ci;
USE `restcountries`;

# Countries table.
DROP TABLE IF EXISTS `countries`;

CREATE TABLE `countries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alpha2` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alpha3` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numeric_code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `calling_code` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capital` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flag_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `region` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,

  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5D66EBADB762D672` (`alpha2`),
  UNIQUE KEY `UNIQ_5D66EBADC065E6E4` (`alpha3`),
  UNIQUE KEY `UNIQ_5D66EBAD95079952` (`numeric_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

# Currencies table. One country might have more than one currency. One currency might be used by
# more than one country.
DROP TABLE IF EXISTS `currencies`;

CREATE TABLE `currencies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `symbol` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_37C4469377153098` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

# We need a many-many relationship since one country may have multiple currencies and one currency
# may be used by multiple countries.
DROP TABLE IF EXISTS `countries_currencies`;

CREATE TABLE `countries_currencies` (
  `country` int(10) unsigned NOT NULL,
  `currency` int(10) unsigned NOT NULL,
  PRIMARY KEY (`currency`,`country`),
  KEY `IDX_403EA91B38248176` (`currency`),
  KEY `IDX_403EA91BF92F3E70` (`country`),
  CONSTRAINT `FK_403EA91B38248176` FOREIGN KEY (`currency`) REFERENCES `currencies` (`id`),
  CONSTRAINT `FK_403EA91BF92F3E70` FOREIGN KEY (`country`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

# Store timezone information. One country may have more than one time zone, and one time zone may
# apply to more than one country.
DROP TABLE IF EXISTS `timezones`;

CREATE TABLE `timezones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,

  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5D66EBAD95079113` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

# We need another many-many relationship since countries can have multiple time zones and time zones
# can apply to multiple countries.
CREATE TABLE `countries_timezones` (
  `country` int(10) unsigned NOT NULL,
  `timezone` int(10) unsigned NOT NULL,
  PRIMARY KEY (`timezone`,`country`),
  KEY `IDX_403EA91B38248931` (`timezone`),
  KEY `IDX_403EA91BF92F3844` (`country`),
  CONSTRAINT `FK_403EA91B38248009` FOREIGN KEY (`timezone`) REFERENCES `timezones` (`id`),
  CONSTRAINT `FK_403EA91BF92F3AB1` FOREIGN KEY (`country`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

# Languages table. Note we avoid using "languages" since "language" is a MySQL reserved word.
DROP TABLE IF EXISTS `langs`;

CREATE TABLE `langs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `iso639_1` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso639_2` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `native_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_37C4469377153098` (`iso639_1`),
  UNIQUE KEY `UNIQ_37C4469377153098` (`iso639_2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

# We need another many-many relationship since countries can have multiple languages and languages
# can be used in multiple countries.
CREATE TABLE `countries_langs` (
   `country` int(10) unsigned NOT NULL,
   `lang` int(10) unsigned NOT NULL,
   PRIMARY KEY (`lang`,`country`),
   KEY `IDX_403EA91B38248177` (`lang`),
   KEY `IDX_403EA91BF92F1094` (`country`),
   CONSTRAINT `FK_403EA91B38248771` FOREIGN KEY (`lang`) REFERENCES `langs` (`id`),
   CONSTRAINT `FK_403EA91BF92F3C65` FOREIGN KEY (`country`) REFERENCES `countries` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
