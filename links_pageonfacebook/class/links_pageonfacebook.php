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

	class links_pageonfacebook{
		
		private $facebook;
		private $linkspage_pref;
		
		public function links_pageonfacebook(){
			
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
		
		private function setLinksPagePref($linkspage_pref){
			$result = $this->linkspage_pref;
			{
				$this->linkspage_pref = $linkspage_pref;
			}
			return $result;
		}
		
		public function getLinksPagePref(){
			$result = $this->linkspage_pref;
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
		
		private static function parse_link($link, $linkspage_pref){
			global $tp, $sql;
			
			$result = array();
			{
				$result['link'] = $link['link_url'];
				$result['picture'] = links_pageonfacebook::load_picture($link['link_button'], $linkspage_pref);
				$result['caption'] = links_pageonfacebook::parse_text($link['link_description']);
				{
					$sql -> db_Select("links_page_cat", "link_category_name", "link_category_id='".$link['link_category']."' ");
					{
						if($row = $sql -> db_Fetch()){
							$result['name'] = $link['link_name']." / ".$row['link_category_name'];
						}else{
							$result['name'] = $link['link_name'];
						}
					}
				}
				$result['actions'] = "{\"name\": \"".LINKS_PAGEONFACEBOOK_0."\", \"link\": \"".$result['link']."\"}";
			}
			return $result;
		}
		
		private static function load_picture($link_button, $linkspage_pref){
			$result = "";
			if(isset($linkspage_pref['link_icon']) && $linkspage_pref['link_icon']){
				if ($link_button) {
					if (strpos($link_button, "http://") !== FALSE) {
						$result = $link_button; 
					}else if(strstr($link_button, "/")){
						if(file_exists(e_BASE.$link_button)){
							$result = "http://".$_SERVER['HTTP_HOST'].e_BASE.$link_button;
						}else if(isset($linkspage_pref['link_icon_empty']) && $linkspage_pref['link_icon_empty']){
							$result = "http://".$_SERVER['HTTP_HOST'].e_PLUGIN_ABS."links_page/images/generic.png";
						}
					}else{
						if(file_exists(e_PLUGIN."links_page/link_images/".$link_button)){
							$result = "http://".$_SERVER['HTTP_HOST'].e_PLUGIN_ABS."links_page/link_images/".$link_button;
						}else if(isset($linkspage_pref['link_icon_empty']) && $linkspage_pref['link_icon_empty']){
							$result = "http://".$_SERVER['HTTP_HOST'].e_PLUGIN_ABS."links_page/images/generic.png";
						}
					}
				}else{
					$result = "http://".$_SERVER['HTTP_HOST'].e_PLUGIN_ABS."links_page/images/generic.png";
				}
			}
			return $result;
		}
		
		public function send($link){
			global $links_pageonfacebook_config;
			
			$result = null;
			{
				$facebook = $this->getFacebook();
				{
					$data = links_pageonfacebook::parse_link($link, $this->getLinksPagePref());
					{
						{
							$data['access_token'] = $links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_ACCESS_TOKEN];
						}
						$result = $facebook->api($links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_PROFILE_ID].'/feed/', 'post', $data);
					}
				}
			}
			return $result;
		}
		
		public static function load(){
			global $links_pageonfacebook_config, $sql, $eArrayStorage;
			
			$result = new links_pageonfacebook();
			{
				{
					$num_rows = $sql -> db_Select("core", "*", "e107_name='links_page' ");
					if($num_rows > 0){
						$row = $sql -> db_Fetch();
						{
							$result->setLinksPagePref($eArrayStorage->ReadArray($row['e107_value']));
						}
					}else{
						$result->setLinksPagePref(array());
					}
				}
				{
					$facebook = new Facebook(
									array(
										'appId'  => $links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_APP_ID],
										'secret' => $links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_APP_SECRET],
									)
								);
					{
						$result->setFacebook($facebook);
					}
				}
			}
			return $result;
		}
	}

?>