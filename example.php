<?php
	if(true){ // debug
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}

	include "BPTalk/BPTalkMapClass.php";

	header("Content-Type: text/json");

	function json($data){
		echo json_encode($data);
	}

	class MainTalk extends BPTalkMap {

		function map(){
			return array(
				// "[ * 로 파라미터를 구분한 request_uri]" => "함수이름",
				"/example/server/status/*" => "serverStatus",  // ex) http://localhost/example/server/status/detail
				"/example/multiparam/*/*/*" => "multiParams",
				"/example/sigleparam/*" => function($param){	// Closure도 사용가능
					echo $param;
					exit();
				}
			);
		}

		function error($code, $message=""){
			$errors = array(
				404 => "No such command.",
				526 => "Provide valid parameters."
			);
			json(array(
				"code" => $code,
				"message" => (@$errors[$code] && $message === "") ? $errors[$code] : $message
			));
		}

		function serverStatus($checkMethod){
			switch($checkMethod){
				case "simple":{
					echo "true";
					break;
				}
				case "detail":{
					json(array(
						"available" => true,
						"time" => time()
					));
					break;
				}
				default:{
					$this->error(5246);
					break;
				}
			}
			exit();
		}

		function multiParams($param1, $param2, $param3){
			echo $param1 . $param2 . $param3;
			exit();
		}
	}

	new MainTalk();