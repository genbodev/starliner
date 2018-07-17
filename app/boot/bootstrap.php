<?php

require_once 'app' . DIRECTORY_SEPARATOR . 'boot' . DIRECTORY_SEPARATOR . 'defines.php';
require_once 'app' . DS . 'core' . DS . 'classloader.php';
require_once 'app' . DS . 'core' . DS . 'route.php';
require_once 'app' . DS . 'core' . DS . 'controller.php';
require_once 'app' . DS . 'core' . DS . 'model.php';
require_once 'app' . DS . 'core' . DS . 'view.php';

require_once 'api.php';

Route::init();