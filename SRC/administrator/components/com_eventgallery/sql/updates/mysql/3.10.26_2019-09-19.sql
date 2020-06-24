CREATE TABLE IF NOT EXISTS `#__eventgallery_imagetypegroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `isdigital` int(1) DEFAULT 0,
  `name` varchar(255) DEFAULT NULL,
  `displayname` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `published` int(1) NULL DEFAULT NULL,
  `modified` timestamp NULL DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `#__eventgallery_imagetypegroup` (`id`, `published`, `name`, `displayname`, `description`, `modified`, `created`) VALUES
(1, 1, 'Prints', 'Prints', 'Printed products', null, null),
(2, 1, 'Digital', 'Digital', 'Product which can be delivered digitaly.', null, null);

ALTER TABLE `#__eventgallery_imagetype` ADD `imagetypegroupid` int(11) DEFAULT NULL after `type`;
