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
	
$eplug_admin = true;
require_once("../../class2.php");
if ( ! getperms('P')) { header('location:'.e_BASE.'index.php'); exit(); }

require_once(e_PLUGIN."links_pageontwitter/include.php");
global $pref, $sql, $tp;

include_lan(e_PLUGIN.'links_pageontwitter/languages/'.e_LANGUAGE.'.php');

$pageid = 'config';
if(isset($_POST['submitted'])){
	{
		$sql->db_Delete("links_pageontwitter_config", " `links_pageontwitter_config_key` = '".LINKS_PAGEONTWITTER_CATEGORIE_LIST."' ");
		{
			$sql->db_Insert("links_pageontwitter_config", array("links_pageontwitter_config_key" => LINKS_PAGEONTWITTER_CATEGORIE_LIST, "links_pageontwitter_config_value" => $tp->toDB(implode(LINKS_PAGEONTWITTER_CATEGORIE_LIST_DELIMITER, $_POST[LINKS_PAGEONTWITTER_CATEGORIE_LIST]))));
		}
	}
	
	{
		$sql->db_Delete("links_pageontwitter_config", " `links_pageontwitter_config_key` = '".LINKS_PAGEONTWITTER_USERCLASS."' ");
		{
			$sql->db_Insert("links_pageontwitter_config", array("links_pageontwitter_config_key" => LINKS_PAGEONTWITTER_USERCLASS, "links_pageontwitter_config_value" => $tp->toDB($_POST[LINKS_PAGEONTWITTER_USERCLASS])));
		}
	}
	
	{
		$sql->db_Delete("links_pageontwitter_config", " `links_pageontwitter_config_key` = '".LINKS_PAGEONTWITTER_ACCESS_TOKEN."' ");
		{
			$sql->db_Insert("links_pageontwitter_config", array("links_pageontwitter_config_key" => LINKS_PAGEONTWITTER_ACCESS_TOKEN, "links_pageontwitter_config_value" => $tp->toDB($_POST[LINKS_PAGEONTWITTER_ACCESS_TOKEN])));
		}
	}
	
	{
		$sql->db_Delete("links_pageontwitter_config", " `links_pageontwitter_config_key` = '".LINKS_PAGEONTWITTER_ACCESS_TOKEN_SECRET."' ");
		{
			$sql->db_Insert("links_pageontwitter_config", array("links_pageontwitter_config_key" => LINKS_PAGEONTWITTER_ACCESS_TOKEN_SECRET, "links_pageontwitter_config_value" => $tp->toDB($_POST[LINKS_PAGEONTWITTER_ACCESS_TOKEN_SECRET])));
		}
	}
	
	{
		{
			{
				$pref[LINKS_PAGEONTWITTER_CONSUMER_KEY] = $_POST[LINKS_PAGEONTWITTER_CONSUMER_KEY];
				$pref[LINKS_PAGEONTWITTER_CONSUMER_SECRET] = $_POST[LINKS_PAGEONTWITTER_CONSUMER_SECRET];
			}
			save_prefs();
		}
		$links_pageontwitter_config = load_links_pageontwitter_config();
	}
}

	function r_categorie_multiple($name, $value_list = array(), $size = 5){
		global $sql;
		
		$result = "";
		{
			$result .= "<select class=\"tbox\" name=\"{$name}[]\" multiple=\"multiple\", size=\"{$size}\">\n";
			{
				$sql -> db_Select("links_page_cat", "link_category_id, link_category_name");
				while($row = $sql -> db_Fetch()) {
					$s = in_array($row['link_category_id'], $value_list) ?  "selected='selected'" : "";
					$result .= "<option  value='".$row['link_category_id']."' ".$s.">".$row['link_category_name']."</option>\n";
				}
			}
			$result .= "</select>\n";
		}
		return $result;
	}

{
	require_once(e_HANDLER."form_handler.php");
    include_once(e_ADMIN."header.php");
    require_once(e_HANDLER.'userclass_class.php');
	
	{
    	$form = new form;
    	{
    		$text = "<div class=\"links_pageontwitter-config\">";
    			$text .= $form->form_open('post', e_PLUGIN_ABS."links_pageontwitter/admin.php", 'links_pageontwitter_config', null, "enctype=\"multipart/form-data\"");
    				$text .= "<table style=\"width: 100%;\">";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".LINKS_PAGEONTWITTER_CONSUMER_KEY_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(LINKS_PAGEONTWITTER_CONSUMER_KEY, 20, $links_pageontwitter_config[LINKS_PAGEONTWITTER_CONSUMER_KEY], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".LINKS_PAGEONTWITTER_CONSUMER_SECRET_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(LINKS_PAGEONTWITTER_CONSUMER_SECRET, 20, $links_pageontwitter_config[LINKS_PAGEONTWITTER_CONSUMER_SECRET], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".LINKS_PAGEONTWITTER_ACCESS_TOKEN_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(LINKS_PAGEONTWITTER_ACCESS_TOKEN, 20, $links_pageontwitter_config[LINKS_PAGEONTWITTER_ACCESS_TOKEN], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".LINKS_PAGEONTWITTER_ACCESS_TOKEN_SECRET_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(LINKS_PAGEONTWITTER_ACCESS_TOKEN_SECRET, 20, $links_pageontwitter_config[LINKS_PAGEONTWITTER_ACCESS_TOKEN_SECRET], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
    				
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".LINKS_PAGEONTWITTER_CATEGORIE_LIST_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= r_categorie_multiple(LINKS_PAGEONTWITTER_CATEGORIE_LIST, $links_pageontwitter_config[LINKS_PAGEONTWITTER_CATEGORIE_LIST]);
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".LINKS_PAGEONTWITTER_USERCLASS_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= r_userclass(LINKS_PAGEONTWITTER_USERCLASS, $links_pageontwitter_config[LINKS_PAGEONTWITTER_USERCLASS]);
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td colspan=\"2\" class=\"forumheader\" style=\"text-align: center;\">";
								$text .= $form->form_button("submit", "submitted", LAN_UPDATE);
							$text .= "</td>";
						$text .= "</tr>";
    				$text .= "</table>";
				$text .= $form->form_close();
    		$text .= "</div>";
    		{
				$ns->tablerender(MAIN_ADMIN_LINKS_PAGEONTWITTER_L2, $text);
			}
    	}
    	
	}
	
	require_once(e_ADMIN.'footer.php');
}

?>