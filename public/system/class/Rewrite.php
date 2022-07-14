<?php 

	Class Rewrite extends Query{
		
		private static $uri;
		private static $elements;
		private static $verifData = true;
		private static $dataPermalink = array();
		private static $params = array();
		private static $page = array();
		
		//1
		// Sets the elements of the uri.
		private static function setElements() {
			$elements = explode('/', Self::$uri);
			foreach(array_keys($elements, '', true) as $key) {
				unset($elements[$key]);
			}
			Self::$elements = array_values($elements);
		}

		//2
		// Set the data permalink.
		private static function setdataPermalink() {
			// Parse permalinks.
			// Checks if the element list is empty.
			if(!empty(Self::$elements)) {
				$code = '^\/?('.Self::$elements[0].')?\/?([a-z0-9\-]+)';
				$regex = '/'.$code.'/i';
				$preg_isValid = preg_match( $regex, Self::$uri, Self::$params );
				foreach(array_keys(Self::$params, '', true) as $key) {
					unset(Self::$params[$key]);
				}
				Self::$params = array_values(Self::$params);
				Self::$dataPermalink = array_values(Self::$params);
			} else {
				Self::$params[0] = Self::$dataPermalink[0] = '/';
				Self::$params[1] = Self::$dataPermalink[1] = 'home';
			}
		}

		//3
		// Add type page into params.
		private static function addTypePageIntoParams() {
			global $lang, $current_lang;
			// Parse the page and archive files.
			if(is_array(Self::$params) && count(Self::$params) > 1){
				// Checks if a search is in the home directory
				if(count(Self::$params) >= 3 && !in_array(Self::$params[1], ['home', 'search'])) {
					Self::$params[] = 'single';
				}
				// set params en en and Sets the permalinks.
				Self::$params[1] = Self::$dataPermalink[1] = isset($lang['en'][array_search(Self::$params[1],$lang[$current_lang])])?$lang['en'][array_search(Self::$params[1],$lang[$current_lang])]: Self::$params[1];
				// Self::$dataPermalink[1] = isset($lang['en'][array_search(Self::$dataPermalink[1],$lang[$current_lang])])?$lang['en'][array_search(Self::$dataPermalink[1],$lang[$current_lang])]: Self::$dataPermalink[1];
				// Sets the pagination params.
				if(count(Self::$params) == 2) {
					Self::$params[] = 'page';
					// Adds the archive file to the params array.
					if(!in_array(Self::$params[1], ['home', 'search']) && !file_exists(INCLUDE_DIR . Self::$params[2].'-'.Self::$params[1].'.php' )) {
						Self::$params[2] = 'archive';
					}
				}
			}
		}

		//4
		// Inverts the pagination params.
		private static function invertParams() {
			// Sets the page s parameters.
			if(isset(Self::$params[2]) && in_array(Self::$params[2], ['page'])) {
				// set params.
				[Self::$params[1], Self::$params[2]] = [Self::$params[2], Self::$params[1]];
			}
		}

		//5
		// Checks if the data is verified
		private static function isDataVerified() {
			// Checks if a permalink exists.
			// Check if the data permalink is in the home directory.
			if(!in_array(Self::$dataPermalink[1], ['home', 'search'])) {
				// Default data permalink.
				$args = array(
					'type'=> Self::$dataPermalink[1],
				);
				// Set the id of the permalink.
				if(isset(Self::$dataPermalink[2])) {
					// Specify a permalink.
					$args['id'] = Self::$dataPermalink[2];
				}
				// Sets if the verification data exists.
				Self::$verifData = (new Query($args))->is_exist();
				// Checks if the data is in the permalink
				if(in_array(Self::$dataPermalink[1], ['search'])) {
					Self::$verifData = true;
				}
			}
		}

		//6
		private static function createPermalink() {
			if(isset(Self::$params[3])) {
				Self::$page[] = Self::$params[3].'-'.Self::$params[1] .'-'. Self::$params[2];
				Self::$page[] = Self::$params[3].'-'.Self::$params[1];
				Self::$page[] = Self::$params[3];
				Self::$page[] = 'singular';
			} else if (isset(Self::$params[2])) {
				if(Self::$params[1] === 'page') {
					Self::$page[] = Self::$params[1].'-'.Self::$params[2];	
					if(!in_array(Self::$params[2], ['home', 'search'])) {
						Self::$page[] = Self::$params[1];
						Self::$page[] = 'singular';
					} else {
						if(!in_array(Self::$params[2], ['home'])) {
							Self::$page[] = 'search';
						} else {
							Self::$page[] = 'home';
						}
					}
				} else {
					if(!in_array(Self::$params[1], ['search'])) {
						Self::$page[] = Self::$params[2].'-'.Self::$params[1];
						Self::$page[] = Self::$params[2];
					} else {
						Self::$page[] = Self::$params[1];
					}
				}
				
			} else {
				Self::$page[] = Self::$params[1];
			}
			Self::$page[] = 'index';
		}

		//7
		// Get a single page.
		private static function getSinglePage() {
			if(isset(Self::$dataPermalink)) {
				$args = array(
					'type' => Self::$dataPermalink[1],
				);
				if(isset(Self::$dataPermalink[2])) {
					$args['id'] = Self::$dataPermalink[2];
				}
				if(!in_array(Self::$dataPermalink[1], ['home'])){ 
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
		}

		//8
		// Get the file from the page.
		private static function getFile() {
			foreach(Self::$page as $val) {
				if(file_exists(INCLUDE_DIR . $val.'.php' )) {
					if(Self::$verifData) {
						include( INCLUDE_DIR . $val.'.php' );
						exit();
					}
				}
			}
		}

		public function __construct() {
			Self::$uri = '/' . ltrim($_SERVER["REQUEST_URI"], '/' );
			Self::setElements();
			Self::setdataPermalink();
			Self::addTypePageIntoParams();
			Self::invertParams();
			Self::isDataVerified();
			Self::createPermalink();
			Self::getSinglePage();
			Self::getFile();
		}

	}