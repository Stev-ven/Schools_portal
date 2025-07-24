<?php
spl_autoload_register(function($classname){
    $filename = ROOT.'assets/server/site/classes/'.str_replace('\\', '/', strtolower($classname)).'.class.php';
    if (is_readable($filename)) {
        require $filename;
    }
});
?>