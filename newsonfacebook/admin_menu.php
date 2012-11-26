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

global $pageid, $newsonfacebook_config;

include_lan(e_PLUGIN.'newsonfacebook/languages/'.e_LANGUAGE.'.php');

$menu['config']['text'] = MAIN_ADMIN_NEWSONFACEBOOK_L1;
$menu['config']['link'] = "admin.php";
$menu['list']['text'] = MAIN_ADMIN_NEWSONFACEBOOK_L2;
$menu['list']['link'] = "admin_list.php";

{
	$fb = newsonfacebook::build_api();
	{
		$menu['install']['text'] = MAIN_ADMIN_NEWSONFACEBOOK_L3;
		$menu['install']['link'] = $fb->getLoginUrl(
				array(
						'scope' => 'read_stream,publish_stream,manage_pages',
						'redirect_uri' => e_SELF,
					)
			);
	}
}
show_admin_menu(MAIN_ADMIN_NEWSONFACEBOOK_L0, $pageid, $menu);

?>