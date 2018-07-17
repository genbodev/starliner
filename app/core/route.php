<?php

class Route {

	private static $routes = array(

		array(
			'pattern' => '~^/$~',
			'module'  => 'main',
			'method'  => 'index'
		),

	);

	public static function init() {

		$module = null;
		$action = 'index';
		$params = array();

		$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

		foreach (self::$routes as $map) {
			if (preg_match($map['pattern'], $url_path, $matches)) {

				array_shift($matches);

				foreach ($matches as $key => $val) {
					$params[$map['params'][$key]] = $val;
				}

				$module = $map['module'];
				$action = $map['method'];

				break;
			}
		}

		if (!is_null($module)) {

			$path_controller = 'app' . DS . 'controllers' . DS . 'c_' . $module . '.php';
			$path_model = 'app' . DS . 'models' . DS . 'm_' . $module . '.php';

			if (file_exists($path_controller)) {
				include $path_controller;
			} else {
				self::show404();
			}

			if (file_exists($path_model)) {
				include $path_model;
			}

			$controller_name = 'Core\C' . ucfirst($module);
			$controller = new $controller_name($params);

			if (method_exists($controller, $action)) {
				$controller->$action();
			} else {
				self::show404();
			}
		} else {
			self::show404();
		}

	}

	private static function show404() {

		$host = 'http://' . $_SERVER['HTTP_HOST'] . '/';
		header('HTTP/1.1 404 Not Found');
		header("Status: 404 Not Found");
		header('Location:' . $host . '404');

	}

}