<?php

// Gets the API url.
function apiUrl() {
	// Check if the API port environment variable API_PORT is set.
	if(!empty(getenv('API_PORT'))) {
		// Returns a human - readable version of the API URL.
		return str_replace('"','',getenv('API_URL').":".getenv('API_PORT'));
	}
	// Returns the API_URL environment variable.
	return getenv('API_URL');
}