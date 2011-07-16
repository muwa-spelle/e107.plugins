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
	
$eplug_admin = true;
require_once("../../class2.php");
if ( ! getperms('P')) { header('location:'.e_BASE.'index.php'); exit(); }

require_once(e_PLUGIN."links_pageonfacebook/include.php");
global $pref, $sql, $tp;

include_lan(e_PLUGIN.'links_pageonfacebook/languages/'.e_LANGUAGE.'/lan_admin.php');

$pageid = 'config';
if(isset($_POST['submitted'])){
	{
		$sql->db_Delete("links_pageonfacebook_config", " `links_pageonfacebook_config_key` = '".LINKS_PAGEONFACEBOOK_CATEGORIE_LIST."' ");
		{
			$sql->db_Insert("links_pageonfacebook_config", array("links_pageonfacebook_config_key" => LINKS_PAGEONFACEBOOK_CATEGORIE_LIST, "links_pageonfacebook_config_value" => $tp->toDB(implode(LINKS_PAGEONFACEBOOK_CATEGORIE_LIST_DELIMITER, $_POST[LINKS_PAGEONFACEBOOK_CATEGORIE_LIST]))));
		}
	}
	
	{
		$sql->db_Delete("links_pageonfacebook_config", " `links_pageonfacebook_config_key` = '".LINKS_PAGEONFACEBOOK_USERCLASS."' ");
		{
			$sql->db_Insert("links_pageonfacebook_config", array("links_pageonfacebook_config_key" => LINKS_PAGEONFACEBOOK_USERCLASS, "links_pageonfacebook_config_value" => $tp->toDB($_POST[LINKS_PAGEONFACEBOOK_USERCLASS])));
		}
	}
	
	{
		$sql->db_Delete("links_pageonfacebook_config", " `links_pageonfacebook_config_key` = '".LINKS_PAGEONFACEBOOK_ACCESS_TOKEN."' ");
		{
			$sql->db_Insert("links_pageonfacebook_config", array("links_pageonfacebook_config_key" => LINKS_PAGEONFACEBOOK_ACCESS_TOKEN, "links_pageonfacebook_config_value" => $tp->toDB($_POST[LINKS_PAGEONFACEBOOK_ACCESS_TOKEN])));
		}
	}
	
	{
		$sql->db_Delete("links_pageonfacebook_config", " `links_pageonfacebook_config_key` = '".LINKS_PAGEONFACEBOOK_PROFILE_ID."' ");
		{
			$sql->db_Insert("links_pageonfacebook_config", array("links_pageonfacebook_config_key" => LINKS_PAGEONFACEBOOK_PROFILE_ID, "links_pageonfacebook_config_value" => $tp->toDB($_POST[LINKS_PAGEONFACEBOOK_PROFILE_ID])));
		}
	}
	
	{
		{
			{
				$pref[LINKS_PAGEONFACEBOOK_APP_ID] = $_POST[LINKS_PAGEONFACEBOOK_APP_ID];
				$pref[LINKS_PAGEONFACEBOOK_APP_SECRET] = $_POST[LINKS_PAGEONFACEBOOK_APP_SECRET];
			}
			save_prefs();
		}
		$links_pageonfacebook_config = load_links_pageonfacebook_config();
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
    		$text = "<div class=\"links_pageonfacebook-config\">";
    			$text .= $form->form_open('post', e_PLUGIN_ABS."links_pageonfacebook/admin.php", 'links_pageonfacebook_config', null, "enctype=\"multipart/form-data\"");
    				$text .= "<table style=\"width: 100%;\">";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".LINKS_PAGEONFACEBOOK_APP_ID_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(LINKS_PAGEONFACEBOOK_APP_ID, 20, $links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_APP_ID], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".LINKS_PAGEONFACEBOOK_APP_SECRET_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(LINKS_PAGEONFACEBOOK_APP_SECRET, 20, $links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_APP_SECRET], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".LINKS_PAGEONFACEBOOK_ACCESS_TOKEN_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(LINKS_PAGEONFACEBOOK_ACCESS_TOKEN, 20, $links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_ACCESS_TOKEN], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".LINKS_PAGEONFACEBOOK_PROFILE_ID_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(LINKS_PAGEONFACEBOOK_PROFILE_ID, 20, $links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_PROFILE_ID], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
    				
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".LINKS_PAGEONFACEBOOK_CATEGORIE_LIST_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= r_categorie_multiple(LINKS_PAGEONFACEBOOK_CATEGORIE_LIST, $links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_CATEGORIE_LIST]);
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".LINKS_PAGEONFACEBOOK_USERCLASS_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= r_userclass(LINKS_PAGEONFACEBOOK_USERCLASS, $links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_USERCLASS]);
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
				$ns->tablerender(MAIN_ADMIN_L2, $text);
			}
    	}
    	
	}
	
	require_once(e_ADMIN.'footer.php');
}

?>