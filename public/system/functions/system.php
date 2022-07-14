<?php

/* OPTIONS SYSTEM */
// Returns the value of the given option.
function get_option(String $option) {
	global $website;
	// Returns a list of options for the current site.
	return ((array) $website->data)[$option];
}

// Dumps an error if DELETE_SHOW_EXCEPTION is set.
function get_error($e) {
	if(getenv('DEV_SHOW_EXCEPTION') == "true") dump($e);
}
/* OPTIONS SYSTEM */