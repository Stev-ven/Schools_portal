<?php
	function getLocalIp(){
		$exec = exec("hostname");
		$hostname = trim($exec);
		$ip = gethostbyname($hostname);
		return ($ip == "127.0.0.1") ? "localhost" : $ip;	
	}

	function getUserIp(){
		return $_SERVER['REMOTE_ADDR'];	
	}
?>