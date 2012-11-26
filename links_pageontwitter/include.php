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

	if(!function_exists('load_links_pageontwitter_config')){
		define("LINKS_PAGEONTWITTER_CONSUMER_KEY", "links_pageontwitter_consumer_key");
		define("LINKS_PAGEONTWITTER_CONSUMER_SECRET", "links_pageontwitter_consumer_secret");
		
		define("LINKS_PAGEONTWITTER_VERIFIER", "links_pageontwitter_verifier");
		define("LINKS_PAGEONTWITTER_VERIFIER_TOKEN", "oauth_token");
		define("LINKS_PAGEONTWITTER_VERIFIER_TOKEN_SECRET", "oauth_token_secret");
		
		define("LINKS_PAGEONTWITTER_ACCESS_TOKEN", "links_pageontwitter_access_token");
		define("LINKS_PAGEONTWITTER_ACCESS_TOKEN_SECRET", "links_pageontwitter_access_token_secret");
		
		define("LINKS_PAGEONTWITTER_CATEGORIE_LIST", "links_pageontwitter_categorie_list");
		define("LINKS_PAGEONTWITTER_CATEGORIE_LIST_DELIMITER", ",");
		
		define("LINKS_PAGEONTWITTER_USERCLASS", "links_pageontwitter_userclass");
		
		define("LINKS_PAGEONTWITTER_SYNCHRONIZATION", "links_pageontwitter_synchronization");
		
			function load_links_pageontwitter_config(){
				global $sql, $pref;
				
				$result = array();
				{
					$result[LINKS_PAGEONTWITTER_CONSUMER_KEY] = isset($pref[LINKS_PAGEONTWITTER_CONSUMER_KEY]) ? $pref[LINKS_PAGEONTWITTER_CONSUMER_KEY] : null;
					$result[LINKS_PAGEONTWITTER_CONSUMER_SECRET] = isset($pref[LINKS_PAGEONTWITTER_CONSUMER_SECRET]) ? $pref[LINKS_PAGEONTWITTER_CONSUMER_SECRET] : null;
					{
						$sql -> db_Select("links_pageontwitter_config");
						while($row = $sql->db_Fetch(MYSQL_ASSOC)){
							$value = $row['links_pageontwitter_config_value'];
							{
								$key = $row['links_pageontwitter_config_key'];
								{
									if($key == LINKS_PAGEONTWITTER_CATEGORIE_LIST){
										$value = explode(LINKS_PAGEONTWITTER_CATEGORIE_LIST_DELIMITER, $value);
									}
									$result[$key] = $value;
								}
							}
						}
					}
				}
				
				return $result;
			}
			$links_pageontwitter_config = load_links_pageontwitter_config();
		
		require_once(e_PLUGIN."links_pageontwitter/class/links_pageontwitter.php");
		require_once(e_HANDLER.'userclass_class.php');
		if(!class_exists('TwitterOAuth')){
			require_once(e_PLUGIN."links_pageontwitter/class/api/twitteroauth.php");
		}
		
		function links_pageontwitter_load_categorie_list(){
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
		
		function update_links_pageontwitter_synchronization($time){
			global $sql;
			{
				$sql->db_Delete("links_pageontwitter_config", " `links_pageontwitter_config_key` = '".LINKS_PAGEONTWITTER_SYNCHRONIZATION."' ");
				{
					$sql->db_Insert("links_pageontwitter_config", array("links_pageontwitter_config_key" => LINKS_PAGEONTWITTER_SYNCHRONIZATION, "links_pageontwitter_config_value" => $time));
				}
			}
		}
	}

?>