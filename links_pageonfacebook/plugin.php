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

	$eplug_name = "Links Seite on Facebook";
	$eplug_version = "0.02";
	$eplug_author = "MUWA-Spelle";
	
	$eplug_url = "http://www.muwa-spelle.com";
	$eplug_email = "info@muwa-spelle.com?subject=Links Seite on Facebook";
	$eplug_description = "This Plugin is for your e107.";
	$eplug_compatible = "e107 v0.7+";
	$eplug_readme = "";

	$eplug_folder = "links_pageonfacebook";

	$eplug_menu_name = null;
	
	$eplug_conffile = "admin.php";
	
	$eplug_icon = $eplug_folder."/images/icon_32.png";
	$eplug_icon_small = $eplug_folder."/images/icon_16.png";
	$eplug_caption = "Links Seite on Facebook";
	
	$eplug_link = FALSE;
	$eplug_link_name = null;
	$eplug_link_url = null;
	$eplug_link_icon = null;
	$eplug_link_perms = e_UC_MEMBER;

	$eplug_done = "\"Links Seite on Facebook\" successfully installed!";

	$upgrade_add_prefs = "";
	$upgrade_remove_prefs = "";
	$upgrade_alter_tables = "";

	$eplug_upgrade_done = "\"Links Seite on Facebook\" successfully updated!";

	$eplug_prefs = array(
									"links_pageonfacebook_app_id",
									"links_pageonfacebook_app_secret",
	);
	$eplug_table_names = array(
									"links_pageonfacebook",
									"links_pageonfacebook_config",
	);
	$eplug_tables = array(
									"CREATE TABLE IF NOT EXISTS `".MPREFIX."links_pageonfacebook_config` (
  `links_pageonfacebook_config_key` varchar(100) NOT NULL DEFAULT '',
  `links_pageonfacebook_config_value` text NOT NULL DEFAULT '',
  PRIMARY KEY (`links_pageonfacebook_config_key`)
) ENGINE=MyISAM",
									"CREATE TABLE IF NOT EXISTS `".MPREFIX."links_pageonfacebook` (
  `id` int(11) NOT NULL DEFAULT '0',
  `fid` char(50) NOT NULL DEFAULT '',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM",
	);

?>