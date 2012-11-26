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

if(!function_exists('load_links_pageonfacebook_config')){
	define("LINKS_PAGEONFACEBOOK_APP_ID", "links_pageonfacebook_app_id");
	define("LINKS_PAGEONFACEBOOK_APP_SECRET", "links_pageonfacebook_app_secret");
	
	define("LINKS_PAGEONFACEBOOK_ACCESS_TOKEN", "links_pageonfacebook_access_token");
	define("LINKS_PAGEONFACEBOOK_PROFILE_ID", "links_pageonfacebook_profile_id");
	
	define("LINKS_PAGEONFACEBOOK_CATEGORIE_LIST", "links_pageonfacebook_categorie_list");
	define("LINKS_PAGEONFACEBOOK_CATEGORIE_LIST_DELIMITER", ",");
	
	define("LINKS_PAGEONFACEBOOK_USERCLASS", "links_pageonfacebook_userclass");
	
	define("LINKS_PAGEONFACEBOOK_SYNCHRONIZATION", "links_pageonfacebook_synchronization");
	
		function load_links_pageonfacebook_config(){
			global $sql, $pref;
			
			$result = array();
			{
				$result[LINKS_PAGEONFACEBOOK_APP_ID] = isset($pref[LINKS_PAGEONFACEBOOK_APP_ID]) ? $pref[LINKS_PAGEONFACEBOOK_APP_ID] : null;
				$result[LINKS_PAGEONFACEBOOK_APP_SECRET] = isset($pref[LINKS_PAGEONFACEBOOK_APP_SECRET]) ? $pref[LINKS_PAGEONFACEBOOK_APP_SECRET] : null;
				{
					$sql -> db_Select("links_pageonfacebook_config");
					while($row = $sql->db_Fetch(MYSQL_ASSOC)){
						$value = $row['links_pageonfacebook_config_value'];
						{
							$key = $row['links_pageonfacebook_config_key'];
							{
								if($key == LINKS_PAGEONFACEBOOK_CATEGORIE_LIST){
									$value = explode(LINKS_PAGEONFACEBOOK_CATEGORIE_LIST_DELIMITER, $value);
								}
								$result[$key] = $value;
							}
						}
					}
				}
			}
			
			return $result;
		}
		$links_pageonfacebook_config = load_links_pageonfacebook_config();
	
	require_once(e_PLUGIN."links_pageonfacebook/class/links_pageonfacebook.php");
	require_once(e_HANDLER.'userclass_class.php');
	if(!class_exists('Facebook')){
		require_once(e_PLUGIN."links_pageonfacebook/class/api/facebook.php");
	}
	
	function links_pageonfacebook_load_categorie_list(){
		global $sql;
		
		$result = array();
		{
			$sql -> db_Select("links_page_cat", "link_category_id");
			while($row = $sql -> db_Fetch()) {
				array_push($result, $row['link_category_id']);
			}
		}
		return $result;
	}
	
	function update_links_pageonfacebook_synchronization($time){
		global $sql;
		{
			$sql->db_Delete("links_pageonfacebook_config", " `links_pageonfacebook_config_key` = '".LINKS_PAGEONFACEBOOK_SYNCHRONIZATION."' ");
			{
				$sql->db_Insert("links_pageonfacebook_config", array("links_pageonfacebook_config_key" => LINKS_PAGEONFACEBOOK_SYNCHRONIZATION, "links_pageonfacebook_config_value" => $time));
			}
		}
	}
}

?>