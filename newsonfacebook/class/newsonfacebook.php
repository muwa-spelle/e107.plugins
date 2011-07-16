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

	class newsonfacebook{
		
		private $facebook;
		
		public function newsonfacebook(){
			
		}
		
		private function setFacebook($facebook){
			$result = $this->facebook;
			{
				$this->facebook = $facebook;
			}
			return $result;
		}
		
		public function getFacebook(){
			$result = $this->facebook;
			{
				
			}
			return $result;
		}
		
		private static function parse_url($url){
			$result = "";
			{
				$result = eval(file_get_contents(e_PLUGIN."newsonfacebook/shortcode/parse_url.sc"));
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
				$result['name'] = $news['news_title'];
				$result['link'] = newsonfacebook::parse_url("http://".$_SERVER['HTTP_HOST'].e_HTTP."news.php?extend.".$news['news_id']);
				$result['caption'] = $news['news_summary'];
				{
					$result['actions'] = "{\"name\": \"".NEWSONFACEBOOK_0."\", \"link\": \"".$result['link']."\"}";
				}
				
				{
					$picture = null;
					{
						if($news['news_thumbnail']){
							if(file_exists(e_IMAGE."newspost_images/thumb_".$news['news_thumbnail'])){
								$picture = "http://".$_SERVER['HTTP_HOST'].e_IMAGE_ABS."newspost_images/thumb_".$news['news_thumbnail'];
							}else if(file_exists(e_IMAGE."newspost_images/".$news['news_thumbnail'])){
								$picture = "http://".$_SERVER['HTTP_HOST'].e_IMAGE_ABS."newspost_images/".$news['news_thumbnail'];
							}
						}
						$result['picture'] = $picture;
					}
				}
				
				{
					if(empty($result['caption'])){
						$caption = $news['news_body'];
						{
							if (substr($caption,0,6) == '[html]'){
								$caption = substr($caption,6);
								if (substr($caption,-7,7) == '[/html]'){
									$caption = substr($caption,0,-7);
								}
							}
							$result['caption'] = $caption;
						}
					}
					$result['caption'] = newsonfacebook::parse_text($result['caption']);
				}
			}
			return $result;
		}
		
		public function send($news){
			global $newsonfacebook_config;
			
			$result = null;
			{
				$facebook = $this->getFacebook();
				{
					$data = newsonfacebook::parse_news($news);
					{
						{
							$data['access_token'] = $newsonfacebook_config[NEWSONFACEBOOK_ACCESS_TOKEN];
						}
						$result = $facebook->api($newsonfacebook_config[NEWSONFACEBOOK_PROFILE_ID].'/feed/', 'post', $data);
					}
				}
			}
			return $result;
		}
		
		public static function load(){
			global $newsonfacebook_config;
			
			$result = new newsonfacebook();
			{
				$facebook = new Facebook(
								array(
									'appId'  => $newsonfacebook_config[NEWSONFACEBOOK_APP_ID],
									'secret' => $newsonfacebook_config[NEWSONFACEBOOK_APP_SECRET],
								)
							);
				{
					$result->setFacebook($facebook);
				}
			}
			return $result;
		}
	}

?>