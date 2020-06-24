CREATE TABLE IF NOT EXISTS `#__gmapfp` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `nom` varchar(200) NOT NULL,
    `alias` varchar(255) NOT NULL,
    `adresse` varchar(200) DEFAULT NULL,
    `adresse2` varchar(200) DEFAULT NULL,
    `ville` varchar(200) DEFAULT NULL,
    `departement` varchar(200) DEFAULT NULL,
    `codepostal` varchar(80) DEFAULT NULL,
    `pay` varchar(200) DEFAULT NULL,
    `tel` varchar(30) DEFAULT NULL,
    `tel2` varchar(30) DEFAULT NULL,
    `fax` varchar(20) DEFAULT NULL,
    `email` varchar(100) DEFAULT NULL,
    `web` varchar(200) DEFAULT NULL,
    `img` varchar(100) DEFAULT NULL,
    `album` tinyint(1) NOT NULL DEFAULT '0',
    `intro` mediumtext DEFAULT NULL,
    `message` mediumtext DEFAULT NULL,
    `horaires_prix` mediumtext DEFAULT NULL,
    `link` varchar(200) DEFAULT NULL,
    `article_id` int(100) DEFAULT '0',
    `icon` varchar(100) DEFAULT NULL,
    `icon_label` varchar(100) DEFAULT NULL,
    `affichage` smallint(1) DEFAULT '0',
    `marqueur` varchar(200) DEFAULT NULL,
    `glng` varchar(12) DEFAULT NULL,
    `glat` varchar(12) DEFAULT NULL,
    `gzoom` varchar(2) DEFAULT NULL,
    `catid` int(10) unsigned NOT NULL DEFAULT '0',
    `userid` int(10) DEFAULT NULL,
    `published` tinyint(1) NOT NULL DEFAULT '0',
    `checked_out` tinyint(1) NOT NULL DEFAULT '0',
    `metadesc` text DEFAULT NULL,
    `metakey` text DEFAULT NULL,
    `ordering` int(11) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__gmapfp_personnalisation` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `nom` varchar(200) NOT NULL,
    `intro_carte` mediumtext DEFAULT NULL,
    `conclusion_carte` mediumtext DEFAULT NULL,
    `intro_detail` mediumtext DEFAULT NULL,
    `conclusion_detail` mediumtext DEFAULT NULL,
    `published` tinyint(1) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__gmapfp_marqueurs` (
  `id` int(11) NOT NULL auto_increment,
  `nom` text NOT NULL,
  `url` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
