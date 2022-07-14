<?php

/* META SYSTEM */
// Returns the meta - information for the given page ID.
function get_meta(INT $id = null) {
	global $id, $title, $description;
	$meta_default = '
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	';
	$meta_default .= '<meta description="'.$description.'">';
	return $meta_default;
}

// Output the meta of a page
function meta(INT $page_id = null) {
	echo get_meta($page_id);
}
/* META SYSTEM */

// Outputs the description of the website.
function description() {
	global $website;
	echo $website->description;
}

// Returns true if there are posts
function have_posts() {
	global $query;
	return $query->have_posts();
}

// Query the post.
function the_post() {
	global $query;
	return $query->the_post();
}

function next_post() {

}

/* TITLE SYSTEM */
function get_title(INT $id = null) {
	global $query, $website;
	$post = $GLOBALS['post'];
	if(is_array($query->post)) {
		if(isset($query->post) && $query->post !== null && !empty($query->post)) {
			return $query->post[$query->current_post]->title;
		}
	} else {
		if($id) {
			return $post->title;
		}
	}
	return $website->data->site_name;
}

function title(INT $id = null) {
	echo get_title($id);
}
/* TITLE SYSTEM */

// Outputs the description of the current post.
function content() {
	global $query;
	if(isset($query->post)) {
		echo $query->post[$query->current_post]->description;
	}
}