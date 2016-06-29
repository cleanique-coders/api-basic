<?php

	define('DOC_ROOT', dirname(__FILE__));

	require_once 'config/app.php';

	// allow from anywhere
	header('Access-Control-Allow-Origin: *'); 

	// only allow these methods
	header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');

	header('Access-Control-Allow-Headers: content-type');

	// return response as json
	header('content-type: application/json charset/utf8');

	// get request method
	$method = $_SERVER['REQUEST_METHOD'];

	if($method == 'POST' || $method == 'GET') {
		$user = User::find('first',[
			'conditions' => [
				'username = ? AND password = ?',
				$_REQUEST['username'],
				md5($_REQUEST['password'])
			]
		]);
		
		if(!empty($user->id)) {
			$token = Token::generate($user->username);
			Token::create($token);

			$u = [
				'user_id' => $user->id,
				'user_username' => $user->username,
			];

			Response::output(['token' => $token, 'user' => $u]);
		} else {
			Response::unauthorized();
		}
	} else {
		Response::invalid();
	}

	// if($method == 'POST') {
	// 	// do login
	// 	if($_POST['username'] == 'nasrul' && $_POST['password'] == 1234) {
	// 		Response::output(
	// 			[
	// 				'token' => rand(100000,9999999) . time(),
	// 				'user' => [
	// 					'id' => 1,
	// 					'name' => 'Nasrul Hazim',
	// 					'username' => 'nasrul',
	// 					'email' => 'nasrul@cleaniquecoders.com'
	// 				]
	// 			]);
	// 	} else {
	// 		Response::error('Invalid Credential');
	// 	}
	// } else {
	// 	Response::invalid();
	// }

	class Response {
		public static function output($value, $message = '', $status = true, $code = 200) {
			echo json_encode([
				'data' => $value,
				'message' => $message,
				'status' => $status,
				'code' => $code
			]);
			exit($code);
		}

		public static function invalid() {
			Response::output(null,'Invalid request','false');
		}

		public static function error($message) {
			Response::output(null, $message, false);
		}

		public static function kesAssoc($data) {
			$_data = [];
			foreach ($data as $key => $value) {
				if(is_string($key)) {
					$_data[$key] = $value;
				}
			}
			return $_data;
		}

		public static function unauthorized() {
			Response::output(null, 'Unauthorized Access', false);
		}
	}