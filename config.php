<?php
	define('_DB_HOST_', 'localhost');
	define('_DB_NAME_', 'citycykler');
	define('_DB_USER_', 'maweb');
	define('_DB_PASSWORD_', 'mast3rdr4gon');
	define('_DB_PREFIX_', '');
	define('_MYSQL_ENGINE_', 'InnoDB');
	define('_CLASSES_', __DIR__.'/Classes');
	define('_CLASSDIR_', glob(_CLASSES_ . '/*' , GLOB_ONLYDIR));
	
	function ClassLoader(string $className)
			{
							$className = str_replace('\\', '/', $className);
							if(file_exists($className)){
									require_once($className);
							} else {
									echo 'ERROR:'. $className;
							}
			}
	foreach(_CLASSDIR_ as $direc) {
			foreach(glob($direc.'/*'.'.php') as $file) {
					ClassLoader($file);
			}
	}

	$db = new DB('mysql:host='._DB_HOST_.';dbname='._DB_NAME_.';charset=utf8',_DB_USER_,_DB_PASSWORD_);
