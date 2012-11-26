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

if (!e107_INIT) { die("Restricted Access"); }

global $pageid;

include_lan(e_PLUGIN.'links_pageontwitter/languages/'.e_LANGUAGE.'.php');

$menu['config']['text'] = MAIN_ADMIN_LINKS_PAGEONTWITTER_L1;
$menu['config']['link'] = "admin.php";
$menu['list']['text'] = MAIN_ADMIN_LINKS_PAGEONTWITTER_L2;
$menu['list']['link'] = "admin_list.php";
$menu['install']['text'] = MAIN_ADMIN_LINKS_PAGEONTWITTER_L3;
$menu['install']['link'] = "admin_install.php";

show_admin_menu(MAIN_ADMIN_LINKS_PAGEONTWITTER_L0, $pageid, $menu);

?>