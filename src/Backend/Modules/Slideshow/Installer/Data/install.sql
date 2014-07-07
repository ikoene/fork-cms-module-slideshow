CREATE TABLE IF NOT EXISTS `slideshow_categories` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `meta_id` int(11) NOT NULL,
 `title` varchar(255) NOT NULL,
 `language` varchar(5) NOT NULL,
 `sequence` int(11),
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `slideshow_galleries` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL,
 `meta_id` int(11) NOT NULL,
 `category_id` int(11) NOT NULL,
 `extra_id` int(4),
 `language` varchar(5) NOT NULL,
 `title` varchar(255) NOT NULL,
 `description` text NOT NULL,
 `filename` varchar(255) NULL,
 `width` int(4) NOT NULL,
 `height` int(4),
 `hidden` enum('N', 'Y') NOT NULL DEFAULT 'N',
 `sequence` int(11) NOT NULL,
 `created_on` datetime NOT NULL,
 `publish_on` datetime NOT NULL,
 `edited_on` datetime,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `slideshow_images` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `gallery_id` int(11) NOT NULL,
 `language` varchar(5) NOT NULL,
 `title` varchar(255),
 `link` varchar(255),
 `description` text,
 `filename` varchar(255) NULL,
 `hidden` enum('N', 'Y') NOT NULL DEFAULT 'N',
 `sequence` int(11),
 PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `slideshow_settings` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `gallery_id` int(11) NOT NULL,
 `animation_type` varchar(25) NOT NULL,
 `slide_direction` varchar(25) NOT NULL,
 `slideshow_speed` int(11) NOT NULL,
 `animation_duration` int(11) NOT NULL,
 `direct_navigation` enum('true', 'false') NOT NULL,
 `control_navigation` enum('true', 'false') NOT NULL,
 `keyboard_navigation` enum('true', 'false') NOT NULL,
 `mousewheel_navigation` enum('true', 'false') NOT NULL,
 `random_order` enum('true', 'false') NOT NULL,
 `auto_animate` enum('true', 'false') NOT NULL,
 `animation_loop` enum('true', 'false') NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
