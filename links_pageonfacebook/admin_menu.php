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

global $pageid, $links_pageonfacebook_config;

include_lan(e_PLUGIN.'links_pageonfacebook/languages/'.e_LANGUAGE.'.php');

$menu['config']['text'] = MAIN_ADMIN_LINKS_PAGEONFACEBOOK_L1;
$menu['config']['link'] = "admin.php";
$menu['list']['text'] = MAIN_ADMIN_LINKS_PAGEONFACEBOOK_L2;
$menu['list']['link'] = "admin_list.php";

{
	$fb = links_pageonfacebook::build_api();
	{
		$menu['install']['text'] = MAIN_ADMIN_LINKS_PAGEONFACEBOOK_L3;
		$menu['install']['link'] = $fb->getLoginUrl(
				array(
						'scope' => 'read_stream,publish_stream,manage_pages',
						'redirect_uri' => e_SELF,
					)
			);
	}
}
show_admin_menu(MAIN_ADMIN_LINKS_PAGEONFACEBOOK_L0, $pageid, $menu);

?>