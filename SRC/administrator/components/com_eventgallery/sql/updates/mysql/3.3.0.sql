ALTER TABLE  `#__eventgallery_watermark` ADD  `image_thumbthresholdsize` int(4) NOT NULL DEFAULT '0' AFTER `image_opacity`;
ALTER TABLE  `#__eventgallery_watermark` ADD  `default` int(1) NOT NULL DEFAULT 0 AFTER `published`;
ALTER TABLE  `#__eventgallery_file` ADD  `url` text NOT NULL AFTER `title`;
ALTER TABLE  `#__eventgallery_staticaddress` ADD  `state` varchar(255) NOT NULL AFTER `city`;
ALTER TABLE  `#__eventgallery_useraddress` ADD  `state` varchar(255) NOT NULL AFTER `city`;
ALTER TABLE  `#__eventgallery_folder` ADD  `foldertypeid` int(11) DEFAULT '0' AFTER `id`;
ALTER TABLE  `#__eventgallery_folder` ADD `metadata` TEXT NOT NULL AFTER `attribs`;

ALTER TABLE `#__eventgallery_imagetype` ADD `weight` DECIMAL( 4, 2 ) DEFAULT  '0' AFTER `size`;
ALTER TABLE `#__eventgallery_imagetype` ADD `depth` DECIMAL( 4, 2 ) DEFAULT  '0' AFTER `size`;
ALTER TABLE `#__eventgallery_imagetype` ADD `height` DECIMAL( 4, 2 ) DEFAULT  '0' AFTER `size`; 
ALTER TABLE `#__eventgallery_imagetype` ADD `width` DECIMAL( 4, 2 ) DEFAULT  '0' AFTER `size`;

update `#__eventgallery_folder` set foldertypeid=1 where folder like '%@%';

