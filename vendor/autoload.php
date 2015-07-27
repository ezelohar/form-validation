<?php
/**
 * Created by PhpStorm.
 * User: ezelohar
 * Date: 7/25/15
 * Time: 9:54 PM
 */


spl_autoload_register(function ($class) {
	$class = str_replace("\\", "/", $class);
	include $class . 'Class.php';
});