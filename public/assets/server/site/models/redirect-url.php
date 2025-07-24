<?php
if(!function_exists("redirectURL")){
	function redirectURL($url){
		header("location:".$url);
		die();
	 }
}
?>