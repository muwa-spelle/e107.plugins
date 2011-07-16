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

require_once(e_PLUGIN."links_pageontwitter/include.php");
global $pref, $sql, $tp;

include_lan(e_PLUGIN.'links_pageontwitter/languages/'.e_LANGUAGE.'/lan_admin.php');
$pageid = 'install';
if(isset($_POST['submitted'])){
	if(isset($_POST[LINKS_PAGEONTWITTER_VERIFIER])){
		$twitter = new TwitterOAuth(
							$links_pageontwitter_config[LINKS_PAGEONTWITTER_CONSUMER_KEY], $links_pageontwitter_config[LINKS_PAGEONTWITTER_CONSUMER_SECRET],
							$_POST[LINKS_PAGEONTWITTER_VERIFIER_TOKEN], $_POST[LINKS_PAGEONTWITTER_VERIFIER_TOKEN_SECRET]
						);
		{
			$token = $twitter->getAccessToken($_POST[LINKS_PAGEONTWITTER_VERIFIER]);
			{
				{
					$sql->db_Delete("links_pageontwitter_config", " `links_pageontwitter_config_key` = '".LINKS_PAGEONTWITTER_ACCESS_TOKEN."' ");
					{
						$sql->db_Insert("links_pageontwitter_config", array("links_pageontwitter_config_key" => LINKS_PAGEONTWITTER_ACCESS_TOKEN, "links_pageontwitter_config_value" => $tp->toDB($token[LINKS_PAGEONTWITTER_VERIFIER_TOKEN])));
					}
				}
				
				{
					$sql->db_Delete("links_pageontwitter_config", " `links_pageontwitter_config_key` = '".LINKS_PAGEONTWITTER_ACCESS_TOKEN_SECRET."' ");
					{
						$sql->db_Insert("links_pageontwitter_config", array("links_pageontwitter_config_key" => LINKS_PAGEONTWITTER_ACCESS_TOKEN_SECRET, "links_pageontwitter_config_value" => $tp->toDB($token[LINKS_PAGEONTWITTER_VERIFIER_TOKEN_SECRET])));
					}
				}
			}
		}
		
		{
			header("location:".e_PLUGIN_ABS."links_pageontwitter/admin.php");
		}
	}
}

{
	require_once(e_HANDLER."form_handler.php");
    include_once(e_ADMIN."header.php");
    
    {
    	$form = new form;
    	{
    		$text = "<div class=\"links_pageontwitter-config\">";
    			$text .= $form->form_open('post', e_PLUGIN_ABS."links_pageontwitter/admin_install.php", 'links_pageontwitter_install', null, "enctype=\"multipart/form-data\"");
    				$text .= "<table style=\"width: 100%;\">";
						$text .= "<tr>";
							$text .= "<td class=\"forumheader3\">".LINKS_PAGEONTWITTER_AUTHORIZE_URL_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								{
									$twitter = new TwitterOAuth(
														$links_pageontwitter_config[LINKS_PAGEONTWITTER_CONSUMER_KEY], $links_pageontwitter_config[LINKS_PAGEONTWITTER_CONSUMER_SECRET]
													);
									{
										$request_token = $twitter->getRequestToken();
										{
											$text .= $form->form_hidden(LINKS_PAGEONTWITTER_VERIFIER_TOKEN, $request_token[LINKS_PAGEONTWITTER_VERIFIER_TOKEN]);
											$text .= $form->form_hidden(LINKS_PAGEONTWITTER_VERIFIER_TOKEN_SECRET, $request_token[LINKS_PAGEONTWITTER_VERIFIER_TOKEN_SECRET]);
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
							$text .= "<td class=\"forumheader3\">".LINKS_PAGEONTWITTER_VERIFIER_L0."</td>";
							$text .= "<td class=\"forumheader2\">";
								$text .= $form->form_text(LINKS_PAGEONTWITTER_VERIFIER, 20, $links_pageontwitter_config[LINKS_PAGEONTWITTER_VERIFIER], 100, 'tbox');
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