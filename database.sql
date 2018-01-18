--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `passwort` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vorname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nachname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `passwortcode` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `passwortcode_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`), UNIQUE (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `securitytokens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(10) NOT NULL,
  `identifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `securitytoken` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE IF NOT EXISTS `favorits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cityname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `weathermain` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `temperatur` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mintemp` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `maxtemp` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pressure` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `humidity` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `windspeed` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `homebase` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sunrise` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sunset` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coordlat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coordlon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
`unit` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;