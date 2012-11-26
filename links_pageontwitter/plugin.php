<?php
/*
	+---------------------------------------------------------------+
	|	e107 website system
	|
	|	(C) MUWA-Spelle 2008-2012
	|	http://www.muwa-spelle.com
	|	info@muwa-spelle.com
	|
	+---------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

	global $PLUGINS_DIRECTORY;

	$eplug_name = "Links Seite on Twitter";
	$eplug_version = "0.02";
	$eplug_author = "MUWA-Spelle";
	
	$eplug_url = "http://www.muwa-spelle.com";
	$eplug_email = "info@muwa-spelle.com?subject=Links Seite on Twitter";
	$eplug_description = "This Plugin is for your e107.";
	$eplug_compatible = "e107 v0.7+";
	$eplug_readme = "";

	$eplug_folder = "links_pageontwitter";

	$eplug_menu_name = null;
	
	$eplug_conffile = "admin.php";
	
	$eplug_icon = $eplug_folder."/images/icon_32.png";
	$eplug_icon_small = $eplug_folder."/images/icon_16.png";
	$eplug_caption = "Links Seite on Twitter";
	
	$eplug_link = FALSE;
	$eplug_link_name = null;
	$eplug_link_url = null;
	$eplug_link_icon = null;
	$eplug_link_perms = e_UC_MEMBER;

	$eplug_done = "\"Links Seite on Twitter\" successfully installed!";

	$upgrade_add_prefs = "";
	$upgrade_remove_prefs = "";
	$upgrade_alter_tables = "";

	$eplug_upgrade_done = "\"Links Seite on Twitter\" successfully updated!";

	$eplug_prefs = array(
									"links_pageontwitter_consumer_key",
									"links_pageontwitter_consumer_secret",
	);
	$eplug_table_names = array(
									"links_pageontwitter",
									"links_pageontwitter_config",
	);
	$eplug_tables = array(
									"CREATE TABLE IF NOT EXISTS `".MPREFIX."links_pageontwitter_config` (
  `links_pageontwitter_config_key` varchar(100) NOT NULL DEFAULT '',
  `links_pageontwitter_config_value` text NOT NULL DEFAULT '',
  PRIMARY KEY (`links_pageontwitter_config_key`)
) ENGINE=MyISAM",
									"CREATE TABLE IF NOT EXISTS `".MPREFIX."links_pageontwitter` (
  `id` int(11) NOT NULL DEFAULT '0',
  `tid` char(50) NOT NULL DEFAULT '',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM",
	);

?>