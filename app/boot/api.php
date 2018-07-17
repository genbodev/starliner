<?php

use Libs\TrainRoute as TrainRoute;

if (preg_match('/^\/api/', $_SERVER['REQUEST_URI'])) {

	if (!isset($_SESSION)) {
		session_start();
	}

	$method = $_SERVER['REQUEST_METHOD'];
	@list($objnm, $objid) = explode('/', $_GET['route']);

	if ($objnm === '') {
		echo "ERROR: no object name";
		exit;
	}

	if ($method === 'GET') {
		if ($objnm === 'auth' && $objid === '') {
			$status = false;
			if (isset($_SESSION['login'])) {
				$status = true;
			}
			echo json_encode(array('status' => $status));
			exit;
		}
		if ($objnm === 'auth' && $objid === 'logout') {
			unset($_SESSION['login']);
			session_destroy();
			echo json_encode(array('status' => true));
			exit;
		}
		if ($objnm === 'soap' && $objid === 'operations') {
			$wsdl = 'https://api.starliner.ru/Api/connect/TrainAPI?wsdl';
			$params = array(
				'trace'    => true,
				'encoding' => 'UTF-8'
			);
			$client = getSoapClient($wsdl, $params);
			$operations = $client->__getFunctions();
			$operations = clear($operations);
			echo json_encode(array('status' => true, 'response' => $operations));
			exit;
		}
		if ($objnm === 'soap' && $objid === 'types') {
			$wsdl = 'https://api.starliner.ru/Api/connect/TrainAPI?wsdl';
			$params = array(
				'trace'    => true,
				'encoding' => 'UTF-8'
			);
			$client = getSoapClient($wsdl, $params);
			$types = $client->__getTypes();
			$types = clear($types);
			echo json_encode(array('status' => true, 'response' => $types));
			exit;
		}
	}

	if ($method === 'POST') {
		if ($objnm === 'auth' && $objid === 'login') {
			$login = trim($_POST['login']);
			$psw = md5(trim($_POST['psw']));
			$terminal = trim($_POST['terminal']);
			$represent_id = trim($_POST['represent_id']);
			$status = false;
			if ($login === 'test' && $psw === 'e4fc2176b93bf70582f8f45ae99e0e54' && $terminal === 'htk_test' && (int)$represent_id === 22400) {
				$_SESSION['login'] = $login;
				$_SESSION['psw'] = trim($_POST['psw']);
				$_SESSION['terminal'] = $terminal;
				$_SESSION['represent_id'] = $represent_id;
				$status = true;
			}
			echo json_encode(array('status' => $status));
			exit;
		}
		if ($objnm === 'train' && $objid === 'route') {
			$login = $_SESSION['login'];
			$psw = $_SESSION['psw'];
			$terminal = $_SESSION['terminal'];
			$represent_id = $_SESSION['represent_id'];
			$train = trim($_POST['train']);
			$from = trim($_POST['from']);
			$to = trim($_POST['to']);
			$day = trim($_POST['day']);
			$month = trim($_POST['month']);
			$status = false;
			if (empty($train) || empty($from) || empty($to) || empty($day) || empty($month)) {
				echo json_encode(array('status' => $status));
				exit;
			}

			ini_set('soap.wsdl_cache_enabled', 0);
			ini_set('soap.wsdl_cache_ttl', 900);
			ini_set('default_socket_timeout', 15);
			$wsdl = 'https://api.starliner.ru/Api/connect/TrainAPI?wsdl';
			$params = array(
				'trace'    => true,
				'encoding' => 'UTF-8'
			);
			$client = getSoapClient($wsdl, $params);
			$auth = new TrainRoute\Auth($login, $psw, $terminal, $represent_id);
			$travelInfo = new TrainRoute\TravelInfo($from, $to, $day, $month);

			$params = array($auth, $train, $travelInfo);

			try {
				$response = $client->__soapCall("trainRoute", $params);
				$status = true;
				echo json_encode(array('status' => $status, 'response' =>  $response->route_list->stop_list));
				exit;
			} catch (SoapFault $e) {
				var_dump($e);
			}

		}
	}

}

function getSoapClient($wsdl, $params) {
	return new SoapClient($wsdl, array(
		//'uri'=>'http://schemas.xmlsoap.org/soap/envelope/',
		//'style'=>SOAP_RPC,
		//'use'=>SOAP_ENCODED,
		//'soap_version'=>SOAP_1_1,
		//'cache_wsdl'=>WSDL_CACHE_NONE,
		//'connection_timeout'=>15,
		'trace'    => $params['trace'],
		'encoding' => $params['encoding'],
		//'exceptions'=>true,
	));
}

function clear($arr) {
	for ($i = 0; $i < count($arr); $i++) {
		$arr[$i] = str_replace("\n", "", $arr[$i]);
		$arr[$i] = str_replace("{ ", "{", $arr[$i]);
	}
	return $arr;
}