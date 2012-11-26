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

	class newsonfacebook{
		
		private $id;
		private $fid;
		private $time;
		private $title;
		private $category;
		private $thumbnail;
		
		public function newsonfacebook(){
			
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
							$result['picture'] = $picture;
						}
					}
				}
				
				if(empty($result['caption'])){
					$caption = $news['news_body'];
					{
						if (substr($caption, 0, 6) == '[html]' && substr($caption, -7, 7) == '[/html]'){
							$caption = newsonfacebook::parse_text(substr($caption, 6, -7));
						}
						$result['caption'] = $caption;
					}
				}
			}
			return $result;
		}
		
		public static function send($api, $news){
			global $newsonfacebook_config;
			
			$result = null;
			{
				$data = newsonfacebook::parse_news($news);
				{
					{
						$data['access_token'] = $newsonfacebook_config[NEWSONFACEBOOK_ACCESS_TOKEN];
					}
					$result = $api->api($newsonfacebook_config[NEWSONFACEBOOK_PROFILE_ID].'/feed/', 'post', $data);
				}
			}
			return $result;
		}
		
		public static function build_api(){
			global $newsonfacebook_config;
			
			$result = new Facebook(
					array(
						'appId'  => $newsonfacebook_config[NEWSONFACEBOOK_APP_ID],
						'secret' => $newsonfacebook_config[NEWSONFACEBOOK_APP_SECRET],
					)
				);
			return $result;
		}
		
		public static function insert($conn, $id, $fid){
			$result = $conn -> db_Insert("newsonfacebook",
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
				$conn->db_Select("newsonfacebook", "*", "`id`='".$id."'");
				if($item = $conn->db_Fetch(MYSQL_ASSOC)){
					$conn -> db_Select("news", "*", "`news_id` = '".$id."'");
					if($row = $conn->db_Fetch(MYSQL_ASSOC)){
						{
							$row += $item;
						}
						$result = newsonfacebook::parse_from_row($row);
					}
				}
			}
			return $result;
		}
		
		public static function remove_by_id($conn, $id){
			$result = $conn->db_Delete("newsonfacebook", "`id`='".$id."'");
			{
				
			}
			return $result;
		}
		
		public static function remove_by_fid($conn, $api, $fid){
			global $newsonfacebook_config;
			
			$result = null;
			{
				try{
					$data = array();
					{
						{
							$data['access_token'] = $newsonfacebook_config[NEWSONFACEBOOK_ACCESS_TOKEN];
						}
						$result = $api->api($fid, 'delete', $data);
					}
				}catch(Exception $e){
					// print_r($e);
				}
				$conn->db_Delete("newsonfacebook", "`fid`='".$fid."'");
			}
			return $result;
		}
		
		public static function parse_from_row($row){
			$result = new newsonfacebook();
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
					$conn -> db_Select("newsonfacebook", "*");
					while($row = $conn->db_Fetch(MYSQL_ASSOC)){
						$list[$row['fid']] = $row;
					}
				}
				
				foreach($list as $fid => $item){
					$conn -> db_Select("news", "*", "`news_id` = '".$item['id']."'");
					if($row = $conn->db_Fetch(MYSQL_ASSOC)){
						{
							$row += $item;
						}
						array_push($result, newsonfacebook::parse_from_row($row));
					}else{
						newsonfacebook::remove_by_id($conn, $item['id']);
					}
				}
			}
			return $result;
		}
	}

?>