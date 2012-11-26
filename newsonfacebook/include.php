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

if(!function_exists('load_newsonfacebook_config')){
	define("NEWSONFACEBOOK_APP_ID", "newsonfacebook_app_id");
	define("NEWSONFACEBOOK_APP_SECRET", "newsonfacebook_app_secret");
	
	define("NEWSONFACEBOOK_ACCESS_TOKEN", "newsonfacebook_access_token");
	define("NEWSONFACEBOOK_PROFILE_ID", "newsonfacebook_profile_id");
	
	define("NEWSONFACEBOOK_CATEGORIE_LIST", "newsonfacebook_categorie_list");
	define("NEWSONFACEBOOK_CATEGORIE_LIST_DELIMITER", ",");
	
	define("NEWSONFACEBOOK_USERCLASS", "newsonfacebook_userclass");
	
	define("NEWSONFACEBOOK_SYNCHRONIZATION", "newsonfacebook_synchronization");
	
		function load_newsonfacebook_config(){
			global $sql, $pref;
			
			$result = array();
			{
				$result[NEWSONFACEBOOK_APP_ID] = isset($pref[NEWSONFACEBOOK_APP_ID]) ? $pref[NEWSONFACEBOOK_APP_ID] : null;
				$result[NEWSONFACEBOOK_APP_SECRET] = isset($pref[NEWSONFACEBOOK_APP_SECRET]) ? $pref[NEWSONFACEBOOK_APP_SECRET] : null;
				{
					$sql -> db_Select("newsonfacebook_config");
					while($row = $sql->db_Fetch(MYSQL_ASSOC)){
						$value = $row['newsonfacebook_config_value'];
						{
							$key = $row['newsonfacebook_config_key'];
							{
								if($key == NEWSONFACEBOOK_CATEGORIE_LIST){
									$value = explode(NEWSONFACEBOOK_CATEGORIE_LIST_DELIMITER, $value);
								}
								$result[$key] = $value;
							}
						}
					}
				}
			}
			
			return $result;
		}
		$newsonfacebook_config = load_newsonfacebook_config();
	
	require_once(e_PLUGIN."newsonfacebook/class/newsonfacebook.php");
	require_once(e_HANDLER.'userclass_class.php');
	if(!class_exists('Facebook')){
		require_once(e_PLUGIN."newsonfacebook/class/api/facebook.php");
	}
	
	function newsonfacebook_load_categorie_list(){
		global $sql;
		
		$result = array();
		{
			$sql -> db_Select("news_category", "category_id");
			while($row = $sql -> db_Fetch()) {
				array_push($result, $row['category_id']);
			}
		}
		return $result;
	}
	
	function update_newsonfacebook_synchronization($time){
		global $sql;
		{
			$sql->db_Delete("newsonfacebook_config", "`newsonfacebook_config_key` = '".NEWSONFACEBOOK_SYNCHRONIZATION."' ");
			{
				$sql->db_Insert("newsonfacebook_config", array("newsonfacebook_config_key" => NEWSONFACEBOOK_SYNCHRONIZATION, "newsonfacebook_config_value" => $time));
			}
		}
	}
	
	function parse_newsonfacebook_data_to_array($qry){
        $result = null;
		if(strpos($qry,'=')){
			if(strpos($qry,'?')!==false){
				$q = parse_url($qry);
				{
					$qry = $q['query'];
				}
			}
			
			{
				$result = array();
				foreach (explode('&', $qry) as $couple){
					list ($key, $val) = explode('=', $couple);
					{
						$result[$key] = $val;
					}
				}
			}
		}
        return $result;
    }
}

?>