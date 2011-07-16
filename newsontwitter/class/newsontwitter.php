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

	class newsontwitter{
		
		const STATUS_LENGTH_MAX = 140;
		
		private $twitter;
		
		public function newsontwitter(){
			
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
		
		private static function parse_url($url){
			$result = "";
			{
				$result = eval(file_get_contents(e_PLUGIN."newsontwitter/shortcode/parse_url.sc"));
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
		
		private static function parse_news($news){
			$result = array();
			{
				$message = "";
				{
					{
						$message .= $news['news_title'];
						$message .= NEWSONTWITTER_0;
						{
							$url = " ".newsontwitter::parse_url("http://".$_SERVER['HTTP_HOST'].e_HTTP."news.php?extend.".$news['news_id']);
							{
								{
									$length = strlen($message) + strlen($url);
									{
										$length = newsontwitter::STATUS_LENGTH_MAX - $length;
									}
									
									{
										$summary = $news['news_summary'];
										{
											if(empty($summary)){
												$summary = $news['news_body'];
												if (substr($summary,0,6) == '[html]'){
													$summary = substr($summary,6);
													if (substr($summary,-7,7) == '[/html]'){
														$summary = substr($summary,0,-7);
													}
												}
											}
											
											$summary = newsontwitter::parse_text($summary);
											if(strlen($summary) > $length){
												$message .= substr($summary, 0, $length-3)."...";
											}else{
												$message .= $summary;
											}
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
		
		public function send($news){
			global $newsontwitter_config;
			
			$result = null;
			{
				$twitter = $this->getTwitter();
				{
					$result = $twitter->post('statuses/update', newsontwitter::parse_news($news));
				}
			}
			return $result;
		}
		
		public static function load(){
			global $newsontwitter_config;
			
			$result = new newsontwitter();
			{
				
				$twitter = new TwitterOAuth(
									$newsontwitter_config[NEWSONTWITTER_CONSUMER_KEY],
									$newsontwitter_config[NEWSONTWITTER_CONSUMER_SECRET],
									$newsontwitter_config[NEWSONTWITTER_ACCESS_TOKEN],
									$newsontwitter_config[NEWSONTWITTER_ACCESS_TOKEN_SECRET]
								);
				{
					$result->setTwitter($twitter);
				}
			}
			return $result;
		}
	}

?>