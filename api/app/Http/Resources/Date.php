<?php

namespace App\Http\Resources;

Class Date {
	public function DateFr($date, $hour = false){
		if($hour){
			$ret = utf8_encode(strftime('%d %b %Y',strtotime($date)));
		}else{
			$ret = utf8_encode(strftime('%d %b %Y %T',strtotime($date)));
		}
		return $ret;
	}
}