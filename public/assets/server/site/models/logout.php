<?php
	require_once "../../../../dir.php";
    require_once "../models/session-start.php";
    require_once "../access/unlock.php";
    require_once "../models/config.php";
    require_once "../models/headers.php";
    require_once "../models/error-reporting.php";
    require_once "../models/default-timezone.php";
    require_once "../models/autoload.php";
	
    $Factory = new Factory();
    $General = $Factory->General();
	$Factory->User()->signOut();
?>