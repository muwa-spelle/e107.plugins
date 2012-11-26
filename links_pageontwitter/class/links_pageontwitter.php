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

	class links_pageontwitter{
		
		const STATUS_LENGTH_MAX = 140;
		
		private $id;
		private $tid;
		private $time;
		private $title;
		private $category;
		private $thumbnail;
		
		public function links_pageontwitter(){
			
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
		
		public static function build_api(){
			global $links_pageontwitter_config;
			
			$result = new TwitterOAuth(
					$links_pageontwitter_config[LINKS_PAGEONTWITTER_CONSUMER_KEY],
					$links_pageontwitter_config[LINKS_PAGEONTWITTER_CONSUMER_SECRET],
					$links_pageontwitter_config[LINKS_PAGEONTWITTER_ACCESS_TOKEN],
					$links_pageontwitter_config[LINKS_PAGEONTWITTER_ACCESS_TOKEN_SECRET]
				);
			return $result;
		}
		
		public static function send($api, $link){
			$result = $api->post('statuses/update', links_pageontwitter::parse_link($link));
			{
				
			}
			return $result;
		}
		
		public static function insert($conn, $id, $tid){
			$result = $conn -> db_Insert("links_pageontwitter",
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
				$conn->db_Select("links_pageontwitter", "*", "`id`='".$id."'");
				if($item = $conn->db_Fetch(MYSQL_ASSOC)){
					$conn -> db_Select("news", "*", "`news_id` = '".$id."'");
					if($row = $conn->db_Fetch(MYSQL_ASSOC)){
						{
							$row += $item;
						}
						$result = links_pageontwitter::parse_from_row($row);
					}
				}
			}
			return $result;
		}
		
		public static function remove_by_id($conn, $id){
			$result = $conn->db_Delete("links_pageontwitter", "`id`='".$id."'");
			{
				
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
				$conn->db_Delete("links_pageontwitter", "`tid`='".$tid."'");
			}
			return $result;
		}
		
		public static function parse_from_row($row){
			$result = new links_pageontwitter();
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
					$conn -> db_Select("links_pageontwitter", "*");
					while($row = $conn->db_Fetch(MYSQL_ASSOC)){
						$list[$row['tid']] = $row;
					}
				}

				foreach($list as $tid => $item){
					$conn -> db_Select("links_page", "*", "`link_id` = '".$item['id']."'");
					if($row = $conn->db_Fetch(MYSQL_ASSOC)){
						{
							$row += $item;
						}
						array_push($result, links_pageontwitter::parse_from_row($row));
					}else{
						links_pageontwitter::remove_by_id($conn, $item['id']);
					}
				}
			}
			return $result;
		}
		
	}

?>