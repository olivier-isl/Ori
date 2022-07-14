<?php


$id = 0;
$title = '';
$description = '';
$post = null;
$stylesheets = array();

// Check if a theme exists.
function theme_exists($file) {
	if(file_exists("theme/{$file}")) {
		return true;
	}
	return false;
}

// Output site infos
function siteInfos($element = null) {
	$data = null;
	// Converts a html element to a string.
	switch($element) {
		case 'title': $data = $GLOBALS['title']; break;
		case 'description': $data = $GLOBALS['description']; break;
		default : $data = ''; break;
	}
	echo $data;
}

// Get a specific header of a page.
function get_header($path= null, $name = null) {
	global $params;
	$templates = array();
		// Set path to a string.
	$path = (string) $path;
	// Checks if a path is set and is not empty.
	if(isset($path) && $path !== '') {
		// INCLUDE_DIR.
		$templates[] = INCLUDE_DIR. $path . "/";
	} else {
		// Add TEMPLATE_DIR to the list of templates
		$templates[] = TEMPLATE_DIR;
	}
    $name = (string) $name;
    if (isset($name) && $name !== '' ) {
        $templates[] = "header-{$name}.php";
    } else {
		$templates[] = 'header.php';
	}
	require $templates[0].$templates[1];
}

// Get a specific footer of a page.
function get_footer($path= null, $name = null) {
	global $params;
	$templates = array();
	$path = (string) $path;
	if(isset($path) && $path !== '') {
		$templates[] = INCLUDE_DIR . $path . "/";
	} else {
		$templates[] = TEMPLATE_DIR;
	}
    $name = (string) $name;
    if (isset($name) && $name !== '' ) {
        $templates[] = "footer-{$name}.php";
    } else {
		$templates[] = 'footer.php';
	}
	require $templates[0].$templates[1];
}

function get_template($path = null, $file = null) {
	
}

// Get a link to a stylesheet
function get_stylesheet($path = null, $file = null, $version = false){
	if((string)$path !== '') {
		$path .= '/';
	}
	$v = '';
	if($version) {
		$v = '?v='. filemtime(INCLUDE_DIR . $path . $file);
	}
	echo '<link rel="stylesheet" href="' . $GLOBALS['protocol'] . TEMPLATE . '/theme/' . $path . $file . $v .'">';
}
