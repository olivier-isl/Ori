<?php
require __DIR__.'/../vendor/autoload.php';
require_once 'class/Env.php';
require_once 'config/config.php';
require_once 'functions/system.php';
require_once 'class/Lang.php';

$current_lang = getenv('DEFAULT_LANG'); // add into options of website

require_once 'class/Query.php';
require_once 'functions/query.php';

require_once "functions/api.php";

$website = new Query(array(
	"type"=>"website"
));

require_once 'functions/options.php';

require_once 'class/Rewrite.php';