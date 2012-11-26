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

require_once(e_PLUGIN."newsontwitter/include.php");
global $pref, $sql, $tp;

include_lan(e_PLUGIN.'newsontwitter/languages/'.e_LANGUAGE.'.php');
$pageid = 'config';
if(isset($_POST['submitted'])){
	{
		$sql->db_Delete("newsontwitter_config", "`newsontwitter_config_key` = '".NEWSONTWITTER_CATEGORIE_LIST."' ");
		{
			$sql->db_Insert("newsontwitter_config", array("newsontwitter_config_key" => NEWSONTWITTER_CATEGORIE_LIST, "newsontwitter_config_value" => $tp->toDB(implode(NEWSONTWITTER_CATEGORIE_LIST_DELIMITER, $_POST[NEWSONTWITTER_CATEGORIE_LIST]))));
		}
	}
	
	{
		$sql->db_Delete("newsontwitter_config", "`newsontwitter_config_key` = '".NEWSONTWITTER_USERCLASS."' ");
		{
			$sql->db_Insert("newsontwitter_config", array("newsontwitter_config_key" => NEWSONTWITTER_USERCLASS, "newsontwitter_config_value" => $tp->toDB($_POST[NEWSONTWITTER_USERCLASS])));
		}
	}
	
	{
		$sql->db_Delete("newsontwitter_config", "`newsontwitter_config_key` = '".NEWSONTWITTER_ACCESS_TOKEN."' ");
		{
			$sql->db_Insert("newsontwitter_config", array("newsontwitter_config_key" => NEWSONTWITTER_ACCESS_TOKEN, "newsontwitter_config_value" => $tp->toDB($_POST[NEWSONTWITTER_ACCESS_TOKEN])));
		}
	}
	
	{
		$sql->db_Delete("newsontwitter_config", "`newsontwitter_config_key` = '".NEWSONTWITTER_ACCESS_TOKEN_SECRET."' ");
		{
			$sql->db_Insert("newsontwitter_config", array("newsontwitter_config_key" => NEWSONTWITTER_ACCESS_TOKEN_SECRET, "newsontwitter_config_value" => $tp->toDB($_POST[NEWSONTWITTER_ACCESS_TOKEN_SECRET])));
		}
	}
	
	{
		{
			{
				$pref[NEWSONTWITTER_CONSUMER_KEY] = $_POST[NEWSONTWITTER_CONSUMER_KEY];
				$pref[NEWSONTWITTER_CONSUMER_SECRET] = $_POST[NEWSONTWITTER_CONSUMER_SECRET];
			}
			save_prefs();
		}
		$newsontwitter_config = load_newsontwitter_config();
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
    		$text = "<div class=\"newsontwitter-config\">";
    			$text .= $form->form_open('post', e_PLUGIN_ABS."newsontwitter/admin.php", 'newsontwitter_config', null, "enctype=\"multipart/form-data\"");
    				$text .= "<table style=\"width: 100%;\">";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".NEWSONTWITTER_CONSUMER_KEY_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(NEWSONTWITTER_CONSUMER_KEY, 20, $newsontwitter_config[NEWSONTWITTER_CONSUMER_KEY], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".NEWSONTWITTER_CONSUMER_SECRET_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(NEWSONTWITTER_CONSUMER_SECRET, 20, $newsontwitter_config[NEWSONTWITTER_CONSUMER_SECRET], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".NEWSONTWITTER_ACCESS_TOKEN_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(NEWSONTWITTER_ACCESS_TOKEN, 20, $newsontwitter_config[NEWSONTWITTER_ACCESS_TOKEN], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".NEWSONTWITTER_ACCESS_TOKEN_SECRET_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(NEWSONTWITTER_ACCESS_TOKEN_SECRET, 20, $newsontwitter_config[NEWSONTWITTER_ACCESS_TOKEN_SECRET], 100, 'tbox');
							$text .= "</td>";
						$text .= "</tr>";
    				
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".NEWSONTWITTER_CATEGORIE_LIST_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= r_categorie_multiple(NEWSONTWITTER_CATEGORIE_LIST, $newsontwitter_config[NEWSONTWITTER_CATEGORIE_LIST]);
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".NEWSONTWITTER_USERCLASS_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= r_userclass(NEWSONTWITTER_USERCLASS, $newsontwitter_config[NEWSONTWITTER_USERCLASS]);
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
				$ns->tablerender(MAIN_ADMIN_NEWSONTWITTER_L2, $text);
			}
    	}
	}
	
	require_once(e_ADMIN.'footer.php');
}

?>