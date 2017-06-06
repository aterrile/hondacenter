<?php
/**
* @author evilnapsis
**/

function show_array($array, $die=0){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
    die();
}

define("ROOT", dirname(__FILE__));

$debug=false;
if($debug){
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
}

include "core/autoload.php";
ob_start();
session_start();

// si quieres que se muestre las consultas SQL debes decomentar la siguiente linea
// Core::$debug_sql = true;

$lb = new Lb();
$lb->start();

?>
