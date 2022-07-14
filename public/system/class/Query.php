<?php

use GuzzleHttp\Client;

Class Query{

	// Sends data to the client.
	public $data;
	public $length;
	public $response;
	public $current_post = 0;
	public $in_the_loop = false;
	public $statusCode;
	
	/**
	 * __construct
	 *
	 * @param  mixed $opts
	 * @return void
	 */
	// Construct a new api.
	function __construct (Array $opts = null) {
		global $post, $id, $title, $description;

		$client = new Client([
			// Base URI is used with relative requests
			'base_uri' => apiUrl(),
			// You can set any number of default request options.
			'timeout'  => 2.0,
		]);
		
		$args = array();
		foreach($opts as $key => $opt) {
			if($key != 'type') {
				$args[$key]	= $opt;
			}
		}

		try {
			// Get a list of all the apis
			$response = $client->request('GET', getenv('API_FOLDER').$opts['type'], array(
				"query"	=> $args
			));
			// Sets the status code of the response.
			$this->statusCode = $response->getStatusCode();
			// Set the reason phrase of the response.
			$this->response = $response->getReasonPhrase();
			// Decodes the JSON response body into an array.
			if($this->statusCode == 200) {
				$this->data = json_decode($response->getBody())->data;
				if(isset(json_decode($response->getBody())->length)) {
					$this->length = json_decode($response->getBody())->length;
				}
			}
		}
		catch (Exception $e) {
			get_error($e);
			return false;
		}
	}
	
	/**
	 * is_exist
	 *
	 * @return void
	 */
	// Checks if the status code is 200.
	function is_exist() {
		if($this->statusCode === 200 && $this->length > 0) return true;
		return false;
	}
	
	/**
	 * next_post
	 *
	 * @return void
	 */
	// Returns the next post.
	public function next_post() {
		$this->current_post++;
		return $this->post;
	}
	
	/**
	 * have_posts
	 *
	 * @return void
	 */
	// Check if there are posts in the loop.
	public function have_posts() {
		if ( $this->current_post + 1 < $this->length ) {
			return true;
		} else if ( $this->current_post + 1 == $this->length && $this->length > 0 ) {
			
			// Do some cleaning up after the loop.
		} else if ( 0 === $this->length ) {
			return false;
		}
	 
		$this->in_the_loop = false;
		return false;
	}
	
	/**
	 * the_post
	 *
	 * @return void
	 */
	// The next post.
	public function the_post() {
		$this->in_the_loop = true;

		$post = $this->next_post();
	}
	
	/**
	 * title
	 *
	 * @return void
	 */
	public function title() {

	}
	
	/**
	 * description
	 *
	 * @return void
	 */
	public function description() {
		
	}

	public function permalink() {
		
	}

	public function reset() {

	}

	public function meta() {

	}

	public function meta_title() {
		
	}
}