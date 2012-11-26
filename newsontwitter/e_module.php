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
		include_lan(e_PLUGIN.'newsontwitter/languages/'.e_LANGUAGE.'.php');
		require_once(e_PLUGIN."newsontwitter/include.php");
			global $newsontwitter_config;
		
		if($newsontwitter_config[NEWSONTWITTER_ACCESS_TOKEN] && $newsontwitter_config[NEWSONTWITTER_ACCESS_TOKEN_SECRET]){
			$s = new db();
			{
				{
					$sync_time = $newsontwitter_config[NEWSONTWITTER_SYNCHRONIZATION];
					{
						$arg = "(news_class REGEXP '".e_CLASS_REGEXP."' AND NOT (news_class REGEXP '(^|,)(".str_replace(",", "|", e_UC_NOBODY).")(,|$)') OR news_class = '".$newsontwitter_config[NEWSONTWITTER_USERCLASS]."') AND ((news_datestamp > '".$sync_time."' and news_start=0) OR news_start > '".$sync_time."') ".( (isset($newsontwitter_config[NEWSONTWITTER_CATEGORIE_LIST]) && implode(',', $newsontwitter_config[NEWSONTWITTER_CATEGORIE_LIST])) ? " AND news_category in (".implode(',', $newsontwitter_config[NEWSONTWITTER_CATEGORIE_LIST]).") " : '' )." AND news_render_type<2";
						{
							$s -> db_Select("news", "MAX(news_start), MAX(news_datestamp)", $arg);
							if($row = $s->db_Fetch(MYSQL_ASSOC)){
								$time = max($row);
								if($time > 0){
									update_newsontwitter_synchronization(max($row));
									{
										$t = newsontwitter::build_api();
										{
											$s -> db_Select("news", "news_thumbnail, news_id, news_title, news_summary, news_body, news_start, news_datestamp", $arg." ORDER BY news_start, news_datestamp");
											{
												$i = new db();
												while($row = $s->db_Fetch(MYSQL_ASSOC)){
													$tid = @newsontwitter::send($t, $row);
													if($tid && isset($tid->id)){
														if($tt = newsontwitter::load_by_id($i, $row['news_id'])){
															newsontwitter::remove_by_tid($i, $t, $tt->getTId());
														}
														newsontwitter::insert($i, $row['news_id'], $tid->id);
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
		}
	}

?>