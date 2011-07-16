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

	if (defined('e107_INIT')) {
		include_lan(e_PLUGIN.'newsonfacebook/languages/'.e_LANGUAGE.'/lan.php');
		require_once(e_PLUGIN."newsonfacebook/include.php");
			global $newsonfacebook_config;
		
		$s = new db();
		{
			{
				$sync_time = $newsonfacebook_config[NEWSONFACEBOOK_SYNCHRONIZATION];
				{
					$arg = "(news_class REGEXP '".e_CLASS_REGEXP."' AND NOT (news_class REGEXP '(^|,)(".str_replace(",", "|", e_UC_NOBODY).")(,|$)') OR news_class = '".$newsonfacebook_config[NEWSONFACEBOOK_USERCLASS]."') AND ((news_datestamp > '".$sync_time."' and news_start=0) OR news_start > '".$sync_time."') ".( (isset($newsonfacebook_config[NEWSONFACEBOOK_CATEGORIE_LIST]) && implode(',', $newsonfacebook_config[NEWSONFACEBOOK_CATEGORIE_LIST])) ? " AND news_category in (".implode(',', $newsonfacebook_config[NEWSONFACEBOOK_CATEGORIE_LIST]).") " : '' )." AND news_render_type<2";
					{
						$s -> db_Select("news", "MAX(news_start), MAX(news_datestamp)", $arg);
						if($row = $s->db_Fetch()){
							$time = max($row);
							if($time > 0){
								update_newsonfacebook_synchronization(max($row));
								{
									$f = newsonfacebook::load();
									{
										$s -> db_Select("news", "news_thumbnail, news_id, news_title, news_summary, news_body, news_start, news_datestamp", $arg." ORDER BY news_start, news_datestamp");
										while($row = $s->db_Fetch()){
											$f->send($row);
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

?>