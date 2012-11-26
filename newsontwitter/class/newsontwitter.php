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

	class newsontwitter{
		
		const STATUS_LENGTH_MAX = 140;
		
		private $id;
		private $tid;
		private $time;
		private $title;
		private $category;
		private $thumbnail;
		
		public function newsontwitter(){
			
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
		
		public function setTId($tid){
			$result = $this->tid;
			{
				$this->tid = $tid;
			}
			return $result;
		}
		
		public function getTId(){
			$result = $this->tid;
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
												if (substr($summary, 0, 6) == '[html]' && substr($summary, -7, 7) == '[/html]'){
													$summary = substr($summary, 6, -7);
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
		
		public static function build_api(){
			global $newsontwitter_config;
			
			$result = new TwitterOAuth(
					$newsontwitter_config[NEWSONTWITTER_CONSUMER_KEY],
					$newsontwitter_config[NEWSONTWITTER_CONSUMER_SECRET],
					$newsontwitter_config[NEWSONTWITTER_ACCESS_TOKEN],
					$newsontwitter_config[NEWSONTWITTER_ACCESS_TOKEN_SECRET]
				);
			return $result;
		}
		
		public static function send($api, $news){
			$result = $api->post('statuses/update', newsontwitter::parse_news($news));;
			{
				
			}
			return $result;
		}
		
		public static function insert($conn, $id, $tid){
			$result = $conn -> db_Insert("newsontwitter",
					array(
							'id' => $id,
							'tid' => $tid,
						)
				);
				
			return $result;
		}
		
		public static function load_by_id($conn, $id){
			$result = null;
			{
				$conn->db_Select("newsontwitter", "*", "`id`='".$id."'");
				if($item = $conn->db_Fetch(MYSQL_ASSOC)){
					$conn -> db_Select("news", "*", "`news_id` = '".$id."'");
					if($row = $conn->db_Fetch(MYSQL_ASSOC)){
						{
							$row += $item;
						}
						$result = newsontwitter::parse_from_row($row);
					}
				}
			}
			return $result;
		}
		
		public static function remove_by_tid($conn, $api, $tid){
			$result = null;
			{
				try{
					$result = $api->delete('statuses/destroy/'.$tid);
				}catch(Exception $e){
					// print_r($e);
				}
				$conn->db_Delete("newsontwitter", "`tid`='".$tid."'");
			}
			return $result;
		}
		
		public static function remove_by_id($conn, $id){
			$result = $conn->db_Delete("newsontwitter", "`id`='".$id."'");
			{
				
			}
			return $result;
		}
		
		public static function parse_from_row($row){
			$result = new newsontwitter();
			{
				$result->setId($row['id']);
				$result->setTId($row['tid']);
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
					$conn -> db_Select("newsontwitter", "*");
					while($row = $conn->db_Fetch(MYSQL_ASSOC)){
						$list[$row['tid']] = $row;
					}
				}
				
				foreach($list as $tid => $item){
					$conn -> db_Select("news", "*", "`news_id` = '".$item['id']."'");
					if($row = $conn->db_Fetch(MYSQL_ASSOC)){
						{
							$row += $item;
						}
						array_push($result, newsontwitter::parse_from_row($row));
					}else{
						newsontwitter::remove_by_id($conn, $item['id']);
					}
				}
			}
			return $result;
		}
	}

?>