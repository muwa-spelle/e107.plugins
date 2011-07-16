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
		include_lan(e_PLUGIN.'links_pageontwitter/languages/'.e_LANGUAGE.'/lan.php');
		require_once(e_PLUGIN."links_pageontwitter/include.php");
			global $links_pageontwitter_config;
		
		$s = new db();
		{
			{
				$sync_time = $links_pageontwitter_config[LINKS_PAGEONTWITTER_SYNCHRONIZATION];
				{
					$arg = "(link_class REGEXP '".e_CLASS_REGEXP."' AND NOT (link_class REGEXP '(^|,)(".str_replace(",", "|", e_UC_NOBODY).")(,|$)') OR link_class = '".$links_pageontwitter_config[LINKS_PAGEONTWITTER_USERCLASS]."') AND link_datestamp > '".$sync_time."' ".( (isset($links_pageontwitter_config[LINKS_PAGEONTWITTER_CATEGORIE_LIST]) && implode(',', $links_pageontwitter_config[LINKS_PAGEONTWITTER_CATEGORIE_LIST])) ? " AND link_category in (".implode(',', $links_pageontwitter_config[LINKS_PAGEONTWITTER_CATEGORIE_LIST]).") " : '')."";
					{
						$s -> db_Select("links_page", "MAX(link_datestamp) as link_datestamp", $arg);
						if(($row = $s->db_Fetch()) && $row[0]){
							update_links_pageontwitter_synchronization($row[0]);
							{
								$f = links_pageontwitter::load();
								{
									$s -> db_Select("links_page", "link_name, link_url, link_description, link_category, link_button", $arg." ORDER BY link_datestamp");
									while($row = $s->db_Fetch()){
										$f->send($row);
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