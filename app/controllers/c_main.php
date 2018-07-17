<?php

namespace Core;

class CMain extends Controller {

	public function index() {

		$this->view->set('title', 'Starliner');
		$this->view->set('site_name', 'Starliner');
		$this->view->set('style', '../../public/main.css');
		$this->view->set('script', '../../public/bundle.js');
		$this->view->glue('app-content', 'app-content.php');
		$this->view->display('index.php');

	}

}