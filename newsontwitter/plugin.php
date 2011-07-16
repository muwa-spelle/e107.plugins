<?php
/*
	+---------------------------------------------------------------+
	|	e107 website system
	|
	|	(C) MUWA-Spelle 2008-2011
	|	http://www.muwa-spelle.com
	|	info@muwa-spelle.com
	|
	+---------------------------------------------------------------+
*/

if (!defined('e107_INIT')) { exit; }

	global $PLUGINS_DIRECTORY;

	$eplug_name = "News on Twitter";
	$eplug_version = "0.01";
	$eplug_author = "MUWA-Spelle";
	
	$eplug_url = "http://www.muwa-spelle.com";
	$eplug_email = "info@muwa-spelle.com?subject=News on Twitter";
	$eplug_description = "This Plugin is for your e107.";
	$eplug_compatible = "e107 v0.7+";
	$eplug_readme = "";

	$eplug_folder = "newsontwitter";

	$eplug_menu_name = null;
	
	$eplug_conffile = "admin.php";
	
	$eplug_icon = $eplug_folder."/images/icon_32.png";
	$eplug_icon_small = $eplug_folder."/images/icon_16.png";
	$eplug_caption = "News on Twitter";
	
	$eplug_link = FALSE;
	$eplug_link_name = null;
	$eplug_link_url = null;
	$eplug_link_icon = null;
	
	$eplug_link_perms = "Everyone";

	$eplug_done = "\"News on Twitter\" successfully installed!";

	$upgrade_add_prefs = "";
	$upgrade_remove_prefs = "";
	$upgrade_alter_tables = "";

	$eplug_upgrade_done = "\"News on Twitter\" successfully updated!";

	$eplug_prefs = array(
									"newsontwitter_consumer_key",
									"newsontwitter_consumer_secret",
	);
	$eplug_table_names = array(
									"newsontwitter_config",
	);
	$eplug_tables = array(
									"CREATE TABLE IF NOT EXISTS `".MPREFIX."newsontwitter_config` (
  `newsontwitter_config_key` varchar(100) NOT NULL DEFAULT '',
  `newsontwitter_config_value` text NOT NULL DEFAULT '',
  PRIMARY KEY (`newsontwitter_config_key`)
) ENGINE=MyISAM",
	);

?>