<?php
	define('_DB_HOST_', 'localhost');
	define('_DB_NAME_', 'citycykler');
	define('_DB_USER_', 'maweb');
	define('_DB_PASSWORD_', 'mast3rdr4gon');
	define('_DB_PREFIX_', '');
	define('_MYSQL_ENGINE_', 'InnoDB');
	
	function ClassLoader(string $className)
	{
	    $className = str_replace('\\', '/', $className);
	    if(file_exists(__DIR__ .'/classes/'. $className . '.php')){
	      require_once(__DIR__ .'/classes/'. $className . '.php');
	    } else {
	      echo 'ERROR: '. __DIR__ .'/classes/'. $className . '.php';
	    }
	}
	spl_autoload_register('ClassLoader');

	$db = new DB('mysql:host='._DB_HOST_.';dbname='._DB_NAME_.';charset=utf8',_DB_USER_,_DB_PASSWORD_);
