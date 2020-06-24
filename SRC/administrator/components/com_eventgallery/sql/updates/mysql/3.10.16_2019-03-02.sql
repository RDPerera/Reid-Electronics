CREATE TABLE IF NOT EXISTS `#__eventgallery_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `folder` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT '',
  `message` text,
  `email` varchar(255),
  `created` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
