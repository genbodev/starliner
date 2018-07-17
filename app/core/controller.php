<?php

namespace Core;

class Controller {

  public $model;
  public $view;
  protected $params;

  public function __construct($params) {

    $this->params = $params;
    $this->view = new View();
    
  }

  public function index() {
    // not use
  }

}
