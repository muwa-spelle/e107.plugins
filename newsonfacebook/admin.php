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

require_once(e_PLUGIN."newsonfacebook/include.php");
global $pref, $sql, $tp;

include_lan(e_PLUGIN.'newsonfacebook/languages/'.e_LANGUAGE.'/lan_admin.php');

$pageid = 'config';
if(isset($_POST['submitted'])){
	{
		$sql->db_Delete("newsonfacebook_config", " `newsonfacebook_config_key` = '".NEWSONFACEBOOK_CATEGORIE_LIST."' ");
		{
			$sql->db_Insert("newsonfacebook_config", array("newsonfacebook_config_key" => NEWSONFACEBOOK_CATEGORIE_LIST, "newsonfacebook_config_value" => $tp->toDB(implode(NEWSONFACEBOOK_CATEGORIE_LIST_DELIMITER, $_POST[NEWSONFACEBOOK_CATEGORIE_LIST]))));
		}
	}
	
	{
		$sql->db_Delete("newsonfacebook_config", " `newsonfacebook_config_key` = '".NEWSONFACEBOOK_USERCLASS."' ");
		{
			$sql->db_Insert("newsonfacebook_config", array("newsonfacebook_config_key" => NEWSONFACEBOOK_USERCLASS, "newsonfacebook_config_value" => $tp->toDB($_POST[NEWSONFACEBOOK_USERCLASS])));
		}
	}
	
	{
		$sql->db_Delete("newsonfacebook_config", " `newsonfacebook_config_key` = '".NEWSONFACEBOOK_ACCESS_TOKEN."' ");
		{
			$sql->db_Insert("newsonfacebook_config", array("newsonfacebook_config_key" => NEWSONFACEBOOK_ACCESS_TOKEN, "newsonfacebook_config_value" => $tp->toDB($_POST[NEWSONFACEBOOK_ACCESS_TOKEN])));
		}
	}
	
	{
		$sql->db_Delete("newsonfacebook_config", " `newsonfacebook_config_key` = '".NEWSONFACEBOOK_PROFILE_ID."' ");
		{
			$sql->db_Insert("newsonfacebook_config", array("newsonfacebook_config_key" => NEWSONFACEBOOK_PROFILE_ID, "newsonfacebook_config_value" => $tp->toDB($_POST[NEWSONFACEBOOK_PROFILE_ID])));
		}
	}
	
	{
		{
			{
				$pref[NEWSONFACEBOOK_APP_ID] = $_POST[NEWSONFACEBOOK_APP_ID];
				$pref[NEWSONFACEBOOK_APP_SECRET] = $_POST[NEWSONFACEBOOK_APP_SECRET];
			}
			save_prefs();
		}
		$newsonfacebook_config = load_newsonfacebook_config();
	}
}

	function r_categorie_multiple($name, $value_list = array(), $size = 5){
		global $sql;
		
		$result = "";
		{
			$result .= "<select class=\"tbox\" name=\"{$name}[]\" multiple=\"multiple\", size=\"{$size}\">\n";
			{
				$sql -> db_Select("news_category", "category_id, category_name");
				while($row = $sql -> db_Fetch()) {
					$s = in_array($row['category_id'], $value_list) ?  "selected='selected'" : "";
					$result .= "<option  value='".$row['category_id']."' ".$s.">".$row['category_name']."</option>\n";
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
    		$text = "<div class=\"newsonfacebook-config\">";
    			$text .= $form->form_open('post', e_PLUGIN_ABS."newsonfacebook/admin.php", 'newsonfacebook_config', null, "enctype=\"multipart/form-data\"");
    				$text .= "<table style=\"width: 100%;\">";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".NEWSONFACEBOOK_APP_ID_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(NEWSONFACEBOOK_APP_ID, 20, $newsonfacebook_config[NEWSONFACEBOOK_APP_ID], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".NEWSONFACEBOOK_APP_SECRET_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(NEWSONFACEBOOK_APP_SECRET, 20, $newsonfacebook_config[NEWSONFACEBOOK_APP_SECRET], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".NEWSONFACEBOOK_ACCESS_TOKEN_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(NEWSONFACEBOOK_ACCESS_TOKEN, 20, $newsonfacebook_config[NEWSONFACEBOOK_ACCESS_TOKEN], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".NEWSONFACEBOOK_PROFILE_ID_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(NEWSONFACEBOOK_PROFILE_ID, 20, $newsonfacebook_config[NEWSONFACEBOOK_PROFILE_ID], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
    				
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".NEWSONFACEBOOK_CATEGORIE_LIST_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= r_categorie_multiple(NEWSONFACEBOOK_CATEGORIE_LIST, $newsonfacebook_config[NEWSONFACEBOOK_CATEGORIE_LIST]);
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".NEWSONFACEBOOK_USERCLASS_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= r_userclass(NEWSONFACEBOOK_USERCLASS, $newsonfacebook_config[NEWSONFACEBOOK_USERCLASS]);
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