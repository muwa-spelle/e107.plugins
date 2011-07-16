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

$result = "";
	if(true){ // furl
		$patterns = array();
		$replacements = array();
		{
			{
				$patterns[0] = '/news\.php\?([0-9]+)\.([0-9]+)\.([0-9]+)/';
				$replacements[0] = 'news$1-$2-$3.html';
		
				$patterns[1] = '/news\.php\?([0-9]+)\.([0-9]+)/';
				$replacements[1] = 'news$1-$2.html';
		
				$patterns[2] = '/news\.php\?item\.([0-9]+)\.([0-9]+)/';
				$replacements[2] = 'news-i$1-$2.html';
		
				$patterns[3] = '/news\.php\?item\.([0-9]+)/';
				$replacements[3] = 'news-i$1.html';
				
				$patterns[4] = '/news\.php\?extend\.([0-9]+)/';
				$replacements[4] = 'news$1.html';
		
				$patterns[5] = '/news\.php\?cat\.([0-9]+)\.([0-9]+)/';
				$replacements[5] = 'news-c$1-$2.html';
		
				$patterns[6] = '/news\.php\?cat\.([0-9]+)/';
				$replacements[6] = 'news-c$1.html';
				
				$patterns[7] = '/news\.php/';
				$replacements[7] = 'news.html';
			}
			$result = preg_replace($patterns, $replacements, $url);
		}
	}else{
		$result = file_get_contents("http://tinyurl.com/api-create.php?url=".$url);
	}
return $result;