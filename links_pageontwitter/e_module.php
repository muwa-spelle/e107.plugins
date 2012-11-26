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
		include_lan(e_PLUGIN.'links_pageontwitter/languages/'.e_LANGUAGE.'.php');
		require_once(e_PLUGIN."links_pageontwitter/include.php");
			global $links_pageontwitter_config;

		if($links_pageontwitter_config[LINKS_PAGEONTWITTER_ACCESS_TOKEN] && $links_pageontwitter_config[LINKS_PAGEONTWITTER_ACCESS_TOKEN_SECRET]){
			$s = new db();
			{
				{
					$sync_time = $links_pageontwitter_config[LINKS_PAGEONTWITTER_SYNCHRONIZATION];
					{
						$arg = "(link_class REGEXP '".e_CLASS_REGEXP."' AND NOT (link_class REGEXP '(^|,)(".str_replace(",", "|", e_UC_NOBODY).")(,|$)') OR link_class = '".$links_pageontwitter_config[LINKS_PAGEONTWITTER_USERCLASS]."') AND link_datestamp > '".$sync_time."' ".( (isset($links_pageontwitter_config[LINKS_PAGEONTWITTER_CATEGORIE_LIST]) && implode(',', $links_pageontwitter_config[LINKS_PAGEONTWITTER_CATEGORIE_LIST])) ? " AND link_category in (".implode(',', $links_pageontwitter_config[LINKS_PAGEONTWITTER_CATEGORIE_LIST]).") " : '')."";
						{
							$s -> db_Select("links_page", "MAX(news_start), MAX(news_datestamp)", $arg);
							if($row = $s->db_Fetch(MYSQL_ASSOC)){
								$time = max($row);
								if($time > 0){
									update_links_pageontwitter_synchronization(max($row));
									{
										$t = links_pageontwitter::build_api();
										{
											$s -> db_Select("links_page", "link_name, link_url, link_description, link_category, link_button", $arg." ORDER BY link_datestamp");
											{
												$i = new db();
												while($row = $s->db_Fetch(MYSQL_ASSOC)){
													$tid = @links_pageontwitter::send($t, $row);
													if($tid && isset($tid->id)){
														if($tt = links_pageontwitter::load_by_id($i, $row['news_id'])){
															links_pageontwitter::remove_by_tid($i, $t, $tt->getTId());
														}
														links_pageontwitter::insert($i, $row['news_id'], $tid->id);
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