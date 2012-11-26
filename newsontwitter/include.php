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

if(!function_exists('load_newsontwitter_config')){
	define("NEWSONTWITTER_CONSUMER_KEY", "newsontwitter_consumer_key");
	define("NEWSONTWITTER_CONSUMER_SECRET", "newsontwitter_consumer_secret");
	
	define("NEWSONTWITTER_VERIFIER", "newsontwitter_verifier");
	define("NEWSONTWITTER_VERIFIER_TOKEN", "oauth_token");
	define("NEWSONTWITTER_VERIFIER_TOKEN_SECRET", "oauth_token_secret");
	
	define("NEWSONTWITTER_ACCESS_TOKEN", "newsontwitter_access_token");
	define("NEWSONTWITTER_ACCESS_TOKEN_SECRET", "newsontwitter_access_token_secret");
	
	define("NEWSONTWITTER_CATEGORIE_LIST", "newsontwitter_categorie_list");
	define("NEWSONTWITTER_CATEGORIE_LIST_DELIMITER", ",");
	
	define("NEWSONTWITTER_USERCLASS", "newsontwitter_userclass");
	
	define("NEWSONTWITTER_SYNCHRONIZATION", "newsontwitter_synchronization");
	
		function load_newsontwitter_config(){
			global $sql, $pref;
			
			$result = array();
			{
				$result[NEWSONTWITTER_CONSUMER_KEY] = isset($pref[NEWSONTWITTER_CONSUMER_KEY]) ? $pref[NEWSONTWITTER_CONSUMER_KEY] : null;
				$result[NEWSONTWITTER_CONSUMER_SECRET] = isset($pref[NEWSONTWITTER_CONSUMER_SECRET]) ? $pref[NEWSONTWITTER_CONSUMER_SECRET] : null;
				{
					$sql -> db_Select("newsontwitter_config");
					while($row = $sql->db_Fetch(MYSQL_ASSOC)){
						$value = $row['newsontwitter_config_value'];
						{
							$key = $row['newsontwitter_config_key'];
							{
								if($key == NEWSONTWITTER_CATEGORIE_LIST){
									$value = explode(NEWSONTWITTER_CATEGORIE_LIST_DELIMITER, $value);
								}
								$result[$key] = $value;
							}
						}
					}
				}
			}
			
			return $result;
		}
		$newsontwitter_config = load_newsontwitter_config();
	
	require_once(e_PLUGIN."newsontwitter/class/newsontwitter.php");
	require_once(e_HANDLER.'userclass_class.php');
	if(!class_exists('TwitterOAuth')){
		require_once(e_PLUGIN."newsontwitter/class/api/twitteroauth.php");
	}
	
	function newsontwitter_load_categorie_list(){
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
	
	function update_newsontwitter_synchronization($time){
		global $sql;
		{
			$sql->db_Delete("newsontwitter_config", "`newsontwitter_config_key` = '".NEWSONTWITTER_SYNCHRONIZATION."' ");
			{
				$sql->db_Insert("newsontwitter_config", array("newsontwitter_config_key" => NEWSONTWITTER_SYNCHRONIZATION, "newsontwitter_config_value" => $time));
			}
		}
	}
}

?>