<?php
session_start();
require __DIR__.'/system/bootstrap.php';

(new Rewrite);

include( INCLUDE_DIR . '404.php' );
die();
