<?php


if($_SERVER['REMOTE_ADDR'] == "127.0.0.1" || $_SERVER['REMOTE_ADDR'] == "localhost"){
    $dev = true;
}else{
    $dev = false;
}

if($dev){
    ini_set('display_errors','on');
    error_reporting(E_ALL);
}

setlocale (LC_TIME, 'fr_BE','fra');
date_default_timezone_set("Europe/Brussels");

$secret_key = "iHqM3m1xruhZohUWc5bvyE4pIO2itvZ3";
$public_key = "DryH6uQnhQRL3YgjnzuM4ZQFDIPrE5vf";

// GET DATA FORM REQUEST
$data = json_decode(file_get_contents("php://input"));

$method = $_SERVER['REQUEST_METHOD'];

$protocol = "http://";

if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    $protocol = "https://";
}

DEFINE( "__RACINE__", $_SERVER['DOCUMENT_ROOT']."/");
DEFINE( 'INCLUDE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/theme/' );
DEFINE( 'TEMPLATE_DIR', $_SERVER['DOCUMENT_ROOT'] . '/system/template/' );
DEFINE( 'TEMPLATE', $_SERVER['HTTP_HOST']);

$class = array_slice(array_slice(scandir("system/class"),2),0, count(array_slice(scandir("system/class"),2))-1);