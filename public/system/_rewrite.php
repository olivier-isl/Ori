<?php 

/* URL REWRITE */

$dataPermalink = array();

$uri = '/' . ltrim($_SERVER["REQUEST_URI"], '/' );
$elements = explode('/', $uri);
foreach(array_keys($elements, '', true) as $key) {
	unset($elements[$key]);
}
$elements = array_values($elements);

if(!empty($elements)) {
	$code = '^\/?('.$elements[0].')?\/?([a-z0-9\-]+)';
	$regex = '/'.$code.'/i';
	$preg_isValid = preg_match( $regex, $uri, $params );
	foreach(array_keys($params, '', true) as $key) {
		unset($params[$key]);
	}
	$params = array_values($params);
	$dataPermalink = array_values($params);
} else {
	$params[0]	= $dataPermalink[0] = '/';
	$params[1] = $dataPermalink[1] = 'home';
}

if(is_array($params) && count($params) > 1){
	if(count($params) >= 3 && !in_array($params[1], ['home', 'search'])) {
		$params[] = 'single';
	}
	$params[1] = isset($lang['en'][array_search($params[1],$lang[$current_lang])])?$lang['en'][array_search($params[1],$lang[$current_lang])]: $params[1];
		$dataPermalink[1] = isset($lang['en'][array_search($dataPermalink[1],$lang[$current_lang])])?$lang['en'][array_search($dataPermalink[1],$lang[$current_lang])]: $dataPermalink[1];
	if(count($params) == 2) {
		$params[] = 'page';
		
		if(!in_array($params[1], ['home', 'search'])) {
			if(!file_exists(INCLUDE_DIR . $params[2].'-'.$params[1].'.php' )) {
				$params[2] = 'archive';
			}
		}
	}
}
if(isset($params[2]) && in_array($params[2], ['page'])) {
	[$params[1], $params[2]] = [$params[2], $params[1]];
}

// dump($dataPermalink);
$verifData = true;
// dump(!in_array($dataPermalink[1], ['home', 'search']));
if(!in_array($dataPermalink[1], ['home', 'search'])) {
	$args = array(
		'type'=> $dataPermalink[1],
	);
	if(isset($dataPermalink[2])) {
		$args['id'] = $dataPermalink[2];
	}
	$verifData = (new Query($args))->is_exist();
	if(in_array($dataPermalink[1], ['search'])) {
		$verifData = true;
	}
}
// dump($verifData);

$pages = array();

if(isset($params[3])) {
	$page[] = $params[3].'-'.$params[1] .'-'. $params[2];
	$page[] = $params[3].'-'.$params[1];
	$page[] = $params[3];
	$page[] = 'singular';
} else if (isset($params[2])) {
	if($params[1] === 'page') {
		$page[] = $params[1].'-'.$params[2];	
		if(!in_array($params[2], ['home', 'search'])) {
			$page[] = $params[1];
			$page[] = 'singular';
		} else {
			if(!in_array($params[2], ['home'])) {
				$page[] = 'search';
			} else {
				$page[] = 'home';
			}
		}
	} else {
		if(!in_array($params[1], ['search'])) {
			$page[] = $params[2].'-'.$params[1];
			$page[] = $params[2];
		} else {
			$page[] = $params[1];
		}
	}
	
} else {
	$page[] = $params[1];
}

$page[] = 'index';

// dump($page);



if(file_exists(INCLUDE_DIR. 'functions.php')) {
	require_once (INCLUDE_DIR. 'functions.php');
}

// Query for a single page.
if(isset($dataPermalink)) {
	$args = array(
		'type' => $dataPermalink[1],
	);
	if(isset($dataPermalink[2])) {
		$args['id'] = $dataPermalink[2];
	}
	if(!in_array($dataPermalink[1], ['home'])){ 
		$query = new Query($args);
	} else {
		// dump(get_option('homepage') );
		if(get_option('homepage') != 'null' ){
			$args = explode("/", get_option('homepage') );
			$query = new Query(array(
				"type"=>"page",
				"id"=> $args[0]
			));
		}
	}
}

foreach($page as $val) {
	if(file_exists(INCLUDE_DIR . $val.'.php' )) {
		if($verifData) {
			include( INCLUDE_DIR . $val.'.php' );
			exit();
		}
	}
}