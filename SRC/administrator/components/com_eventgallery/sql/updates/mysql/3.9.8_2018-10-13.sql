ALTER TABLE `#__eventgallery_folder` ADD  `googlephotosaccountid` int(11) DEFAULT '0' after `foldertypeid`;

CREATE TABLE IF NOT EXISTS `#__eventgallery_googlephotos_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clientid` varchar(250) DEFAULT '0',
  `secret` varchar(250) DEFAULT NULL,
  `refreshtoken` VARCHAR( 250 ) NOT NULL,
  `name` text,
  `description` text,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `published` tinyint(4) NOT NULL DEFAULT '1',
  `ordering` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;