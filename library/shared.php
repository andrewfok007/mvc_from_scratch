<?php

// Check if enviroment is development and display errors
function setReporting(){
	
	if(DEVELOPMENT_ENVIROMENT == true){
		error_reporting(E_ALL);
		ini_set('display_errors', 'On');
	} else {
		error_reporting(E_ALL);
		ini_set('display_errors', 'Off');
		ini_set('log_errors', 'On');
		ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
	}
}


// Check for Magic quotes and remove them
function stripSlashesDeep($value){
	$value = is_array($value) ? array_map('stripSlashesDeep', $value) : stripcslashes($value);
	return $value;
}

function removeMagicQuotes(){
	if(get_magic_quotes_gpc()){
		$_GET = stripSlashesDeep($_GET);
		$_POST = stripSlashesDeep($_POST);
		$_COOKIE = stripSlashesDeep($_COOKIE);
	}
}

// Check register globals and remove them
function unregisterGlobals(){
	if(ini_get('register_globals')){
		$array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
		foreach ($array as $value) {
			foreach ($GLOBALS[$value] as $key => $val) {
				if($var === $GLOBALS[$key]){
					unset($GLOBALS[$key]);
				}
			}
		}
	}
}

function callHook(){

	global $url; // e.g. todo.com/items/view/1/first-item

	$urlArray = array();
	$urlArray = explode('/', $url);

	$controller = $urlArray[0]; // items
	array_shift($urlArray);
	
	$action = $urlArray[0]; // view
	array_shift($urlArray);
	
	$queryString = $urlArray; // 1 first-item

	$controllerName = $controller;
	$controller = ucwords($controller);
	$model = rtrim($controller, 's'); // item
	$controller .= 'Controller'; // itemsController
	$dispatch = new $controller($model, $controllerName, $action);

	if((int)method_exists($controller, $action)){
		call_user_func_array(array($dispatch, $action), $queryString);
	} else {

	}
}

function __autoload($className) {
    if (file_exists(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php')) {
        require_once(ROOT . DS . 'library' . DS . strtolower($className) . '.class.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'controllers' . DS . strtolower($className) . '.php');
    } else if (file_exists(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php')) {
        require_once(ROOT . DS . 'application' . DS . 'models' . DS . strtolower($className) . '.php');
    } else {
        /* Error Generation Code Here */
    }
}
 
setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook();