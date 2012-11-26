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

	if (defined('e107_INIT')) {
		include_lan(e_PLUGIN.'newsonfacebook/languages/'.e_LANGUAGE.'.php');
		require_once(e_PLUGIN."newsonfacebook/include.php");
			global $newsonfacebook_config;
		
		
		if($newsonfacebook_config[NEWSONFACEBOOK_ACCESS_TOKEN]){
			$s = new db();
			{
				{
					$sync_time = $newsonfacebook_config[NEWSONFACEBOOK_SYNCHRONIZATION];
					{
						$arg = "(news_class REGEXP '".e_CLASS_REGEXP."' AND NOT (news_class REGEXP '(^|,)(".str_replace(",", "|", e_UC_NOBODY).")(,|$)') OR news_class = '".$newsonfacebook_config[NEWSONFACEBOOK_USERCLASS]."') AND ((news_datestamp > '".$sync_time."' and news_start=0) OR news_start > '".$sync_time."') ".( (isset($newsonfacebook_config[NEWSONFACEBOOK_CATEGORIE_LIST]) && implode(',', $newsonfacebook_config[NEWSONFACEBOOK_CATEGORIE_LIST])) ? " AND news_category in (".implode(',', $newsonfacebook_config[NEWSONFACEBOOK_CATEGORIE_LIST]).") " : '' )." AND news_render_type<2";
						{
							$s -> db_Select("news", "MAX(news_start), MAX(news_datestamp)", $arg);
							if($row = $s->db_Fetch(MYSQL_ASSOC)){
								$time = max($row);
								if($time > 0){
									update_newsonfacebook_synchronization(max($row));
									{
										$f = newsonfacebook::build_api();
										{
											$s -> db_Select("news", "news_thumbnail, news_id, news_title, news_summary, news_body, news_start, news_datestamp", $arg." ORDER BY news_start, news_datestamp");
											{
												$i = new db();
												while($row = $s->db_Fetch(MYSQL_ASSOC)){
													$fid = @newsonfacebook::send($f, $row);
													if((!empty($fid)) && isset($fid['id'])){
														if($ff = newsonfacebook::load_by_id($i, $row['news_id'])){
															newsonfacebook::remove_by_fid($i, $f, $ff->getFId());
														}
														newsonfacebook::insert($i, $row['news_id'], $fid['id']);
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
				$s->db_Close();
			}
		}else if(isset($_REQUEST["code"])){
			$token = @file_get_contents("https://graph.facebook.com/oauth/access_token?client_id=".$newsonfacebook_config[NEWSONFACEBOOK_APP_ID]."&redirect_uri=".urlencode(e_SELF)."&client_secret=".$newsonfacebook_config[NEWSONFACEBOOK_APP_SECRET]."&code=".$_REQUEST["code"]);
			if($token){
				{
					$token = parse_newsonfacebook_data_to_array($token);
					if(isset($token['access_token'])){
						$token = $token['access_token'];
					}else{
						$token = null;
					}
				}
				
				if($token){
					{
						$sql->db_Delete("newsonfacebook_config", "`newsonfacebook_config_key` = '".NEWSONFACEBOOK_ACCESS_TOKEN."' ");
						{
							$sql->db_Insert("newsonfacebook_config", array("newsonfacebook_config_key" => NEWSONFACEBOOK_ACCESS_TOKEN, "newsonfacebook_config_value" => $token));
						}
					}
					$newsonfacebook_config[NEWSONFACEBOOK_ACCESS_TOKEN] = $token;
				}
			}
		}
	}

?>