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

	class links_pageonfacebook{
		
		private $id;
		private $fid;
		private $time;
		private $title;
		private $category;
		private $thumbnail;
		
		public function links_pageonfacebook(){
			
		}
		
		public function setId($id){
			$result = $this->id;
			{
				$this->id = $id;
			}
			return $result;
		}
		
		public function getId(){
			$result = $this->id;
			{
				
			}
			return $result;
		}
		
		public function setFId($fid){
			$result = $this->fid;
			{
				$this->fid = $fid;
			}
			return $result;
		}
		
		public function getFId(){
			$result = $this->fid;
			{
				
			}
			return $result;
		}
		
		private function setTime($time){
			$result = $this->time;
			{
				$this->time = $time;
			}
			return $result;
		}
		
		public function getTime(){
			$result = $this->time;
			{
				
			}
			return $result;
		}
		
		private function setTitle($title){
			$result = $this->title;
			{
				$this->title = $title;
			}
			return $result;
		}
		
		public function getTitle(){
			$result = $this->title;
			{
				
			}
			return $result;
		}
		
		private function setCategory($category){
			$result = $this->category;
			{
				$this->category = $category;
			}
			return $result;
		}
		
		public function getCategory(){
			$result = $this->category;
			{
				
			}
			return $result;
		}
		
		private function setThumbnail($thumbnail){
			$result = $this->thumbnail;
			{
				$this->thumbnail = $thumbnail;
			}
			return $result;
		}
		
		public function getThumbnail(){
			$result = $this->thumbnail;
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
		
		public function send($api, $link){
			global $links_pageonfacebook_config;
			
			$result = null;
			{
				$lc = new linkclass();
				{
					$data = links_pageonfacebook::parse_link($link, $lc -> getLinksPagePref());
					{
						{
							$data['access_token'] = $links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_ACCESS_TOKEN];
						}
						$result = $api->api($links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_PROFILE_ID].'/feed/', 'post', $data);
					}
				}
			}
			return $result;
		}
		
		
		public static function build_api(){
			global $links_pageonfacebook_config;
			
			$result = new Facebook(
					array(
						'appId'  => $links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_APP_ID],
						'secret' => $links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_APP_SECRET],
					)
				);
			return $result;
		}
		
		public static function insert($conn, $id, $fid){
			$result = $conn -> db_Insert("links_pageonfacebook",
					array(
							'id' => $id,
							'fid' => $fid,
						)
				);
				
			return $result;
		}
		
		public static function load_by_id($conn, $id){
			$result = null;
			{
				$conn->db_Select("links_pageonfacebook", "*", "`id`='".$id."'");
				if($item = $conn->db_Fetch(MYSQL_ASSOC)){
					$conn -> db_Select("news", "*", "`news_id` = '".$id."'");
					if($row = $conn->db_Fetch(MYSQL_ASSOC)){
						{
							$row += $item;
						}
						$result = links_pageonfacebook::parse_from_row($row);
					}
				}
			}
			return $result;
		}
		
		public static function remove_by_id($conn, $id){
			$result = $conn->db_Delete("links_pageonfacebook", "`id`='".$id."'");
			{
				
			}
			return $result;
		}
		
		public static function remove_by_fid($conn, $api, $fid){
			global $links_pageonfacebook_config;
			
			$result = null;
			{
				try{
					$data = array();
					{
						{
							$data['access_token'] = $links_pageonfacebook_config[LINKS_PAGEONFACEBOOK_ACCESS_TOKEN];
						}
						$result = $api->api($fid, 'delete', $data);
					}
				}catch(Exception $e){
					// print_r($e);
				}
				$conn->db_Delete("links_pageonfacebook", "`fid`='".$fid."'");
			}
			return $result;
		}
		
		public static function parse_from_row($row){
			$result = new links_pageonfacebook();
			{
				$result->setId($row['id']);
				$result->setFId($row['fid']);
				$result->setTime($row['time']);
				$result->setTitle($row['news_title']);
				$result->setCategory($row['news_category']);
				$result->setThumbnail($row['news_thumbnail']);
			}
			return $result;
		}
		
		public static function list_all($conn){
			$result = array();
			
			{
				$list = array();
				{
					$conn -> db_Select("links_pageonfacebook", "*");
					while($row = $conn->db_Fetch(MYSQL_ASSOC)){
						$list[$row['fid']] = $row;
					}
				}

				foreach($list as $fid => $item){
					$conn -> db_Select("links_page", "*", "`link_id` = '".$item['id']."'");
					if($row = $conn->db_Fetch(MYSQL_ASSOC)){
						{
							$row += $item;
						}
						array_push($result, links_pageonfacebook::parse_from_row($row));
					}else{
						links_pageonfacebook::remove_by_id($conn, $item['id']);
					}
				}
			}
			return $result;
		}
	}

?>