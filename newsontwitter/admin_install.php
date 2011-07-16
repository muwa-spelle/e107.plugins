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

require_once(e_PLUGIN."newsontwitter/include.php");
global $pref, $sql, $tp;

include_lan(e_PLUGIN.'newsontwitter/languages/'.e_LANGUAGE.'/lan_admin.php');
$pageid = 'install';
if(isset($_POST['submitted'])){
	if(isset($_POST[NEWSONTWITTER_VERIFIER])){
		$twitter = new TwitterOAuth(
							$newsontwitter_config[NEWSONTWITTER_CONSUMER_KEY], $newsontwitter_config[NEWSONTWITTER_CONSUMER_SECRET],
							$_POST[NEWSONTWITTER_VERIFIER_TOKEN], $_POST[NEWSONTWITTER_VERIFIER_TOKEN_SECRET]
						);
		{
			$token = $twitter->getAccessToken($_POST[NEWSONTWITTER_VERIFIER]);
			{
				{
					$sql->db_Delete("newsontwitter_config", " `newsontwitter_config_key` = '".NEWSONTWITTER_ACCESS_TOKEN."' ");
					{
						$sql->db_Insert("newsontwitter_config", array("newsontwitter_config_key" => NEWSONTWITTER_ACCESS_TOKEN, "newsontwitter_config_value" => $tp->toDB($token[NEWSONTWITTER_VERIFIER_TOKEN])));
					}
				}
				
				{
					$sql->db_Delete("newsontwitter_config", " `newsontwitter_config_key` = '".NEWSONTWITTER_ACCESS_TOKEN_SECRET."' ");
					{
						$sql->db_Insert("newsontwitter_config", array("newsontwitter_config_key" => NEWSONTWITTER_ACCESS_TOKEN_SECRET, "newsontwitter_config_value" => $tp->toDB($token[NEWSONTWITTER_VERIFIER_TOKEN_SECRET])));
					}
				}
			}
		}
		
		{
			header("location:".e_PLUGIN_ABS."newsontwitter/admin.php");
		}
	}
}

{
	require_once(e_HANDLER."form_handler.php");
    include_once(e_ADMIN."header.php");
    
    {
    	$form = new form;
    	{
    		$text = "<div class=\"newsontwitter-config\">";
    			$text .= $form->form_open('post', e_PLUGIN_ABS."newsontwitter/admin_install.php", 'newsontwitter_install', null, "enctype=\"multipart/form-data\"");
    				$text .= "<table style=\"width: 100%;\">";
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".NEWSONTWITTER_AUTHORIZE_URL_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								{
									$twitter = new TwitterOAuth(
														$newsontwitter_config[NEWSONTWITTER_CONSUMER_KEY], $newsontwitter_config[NEWSONTWITTER_CONSUMER_SECRET]
													);
									{
										$request_token = $twitter->getRequestToken();
										{
											$text .= $form->form_hidden(NEWSONTWITTER_VERIFIER_TOKEN, $request_token[NEWSONTWITTER_VERIFIER_TOKEN]);
											$text .= $form->form_hidden(NEWSONTWITTER_VERIFIER_TOKEN_SECRET, $request_token[NEWSONTWITTER_VERIFIER_TOKEN_SECRET]);
										}
										
										{
											$url = $twitter->getAuthorizeURL($request_token);
											{
												$text .= "<a href=\"".$url."\" target=\"_blank\">".$url."</a>";
											}
										}
									}
								}
							$text .= "</td>";
						$text .= "</tr>";
						
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".NEWSONTWITTER_VERIFIER_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(NEWSONTWITTER_VERIFIER, 20, $newsontwitter_config[NEWSONTWITTER_VERIFIER], 100, 'tbox');
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