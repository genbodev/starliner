<?php

function classLoader($className) {

	$class_pieces = explode('\\', $className);

	switch (strtolower($class_pieces[0])) {

		case 'core':
			require 'app' . DS . strtolower($class_pieces[0]) . DS . strtolower($class_pieces[1]) . '.php';
			break;
		case 'libs':
			require 'app' .DS. strtolower($class_pieces[0]) . DS . $class_pieces[1] . DS . $class_pieces[2] . '.php';
			//require 'app/libs/TrainRoute/TrainRoute.php';
			break;
		default:
			break;

	}

}

spl_autoload_register('classLoader');