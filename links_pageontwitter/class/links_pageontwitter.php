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

	class links_pageontwitter{
		
		const STATUS_LENGTH_MAX = 140;
		
		private $twitter;
		
		public function links_pageontwitter(){
			
		}
		
		private function setTwitter($twitter){
			$result = $this->twitter;
			{
				$this->twitter = $twitter;
			}
			return $result;
		}
		
		public function getTwitter(){
			$result = $this->twitter;
			{
				
			}
			return $result;
		}
		
		private static function parse_text($text){
			global $tp;
			
			$result = "";
			{
				$text = trim(strip_tags(preg_replace('/<(head|title|style|script)[^>]*>.*?<\/\\1>/s','',$tp->toHTML($text, true))));
				{
					$result = html_entity_decode($text);
				}
			}
			return $result;
		}
		
		private static function parse_link($link){
			global $tp, $sql;
			
			$result = array();
			{
				$message = "";
				{
					{
						{
							{
								$sql -> db_Select("links_page_cat", "link_category_name", "link_category_id='".$link['link_category']."' ");
								{
									if($row = $sql -> db_Fetch()){
										$message .= $link['link_name']." / ".$row['link_category_name'];
									}else{
										$message .= $link['link_name'];
									}
								}
							}
							$message .= LINKS_PAGEONTWITTER_0;
						}
						
						{
							$url .= " ".$link['link_url'];
							{
								{
									$length = strlen($message) + strlen($url);
									{
										$length = links_pageontwitter::STATUS_LENGTH_MAX - $length;
									}
									
									{
										$summary = links_pageontwitter::parse_text($link['link_description']);
										if(strlen($summary) > $length){
											$message .= substr($summary, 0, $length-3)."...";
										}else{
											$message .= $summary;
										}
									}
								}
								$message .= $url;
							}
						}
					}
					$result['status'] = $message;
				}
			}
			return $result;
		}
		
		public function send($link){
			global $links_pageontwitter_config;
			
			$result = null;
			{
				$twitter = $this->getTwitter();
				{
					$result = $twitter->post('statuses/update', links_pageontwitter::parse_link($link));
				}
			}
			return $result;
		}
		
		public static function load(){
			global $links_pageontwitter_config, $sql, $eArrayStorage;
			
			$result = new links_pageontwitter();
			{
				$twitter = new TwitterOAuth(
									$links_pageontwitter_config[LINKS_PAGEONTWITTER_CONSUMER_KEY],
									$links_pageontwitter_config[LINKS_PAGEONTWITTER_CONSUMER_SECRET],
									$links_pageontwitter_config[LINKS_PAGEONTWITTER_ACCESS_TOKEN],
									$links_pageontwitter_config[LINKS_PAGEONTWITTER_ACCESS_TOKEN_SECRET]
								);
				{
					$result->setTwitter($twitter);
				}
			}
			return $result;
		}
	}

?>