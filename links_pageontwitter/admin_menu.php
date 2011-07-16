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

if (!e107_INIT) { die("Restricted Access"); }

global $pageid;

include_lan(e_PLUGIN.'links_pageontwitter/languages/'.e_LANGUAGE.'/lan_admin.php');

$menu['config']['text'] = MAIN_ADMIN_L1;
$menu['config']['link'] = "admin.php";
$menu['install']['text'] = MAIN_ADMIN_L3;
$menu['install']['link'] = "admin_install.php";

show_admin_menu(MAIN_ADMIN_L0, $pageid, $menu);

?>