<?php
	interface BPTalkMapInterface {
		public function addMap($pattern, $actionname = null);
		public function action($uri = null);
	}

	abstract class BPTalkMap implements BPTalkMapInterface {
		private $pattern; // 정규식
		private $actions; // 함수명
		public function __construct($do = true){
			$this->actions = array();
			$this->pattern = array();
			if($this->map()){
				$this->addMap($this->map()); // map()의 리턴깂으로 매핑
			}
			if($do){
				$this->action();
			}
		}
		public function addMap($pattern, $actionname = null){
			$action_array = array();
			if(is_array($pattern)){ // 반복 매핑
				$action_array = $pattern;
				foreach ($action_array as $s_pattern => $s_actioname) {
					$this->mapping($s_pattern, $s_actioname);
				}
			}else{
				$this->mapping($pattern, $actioname); // 1회 매핑
			}
		}
		private function mapping($pattern, $actioname){
			//정규식 작성
			$pattern = '/^' . str_replace('\*', '([a-zA-Z0-9-_+=!]+)', str_replace('/','\/',preg_quote($pattern))) . '$/i';
			array_push($this->pattern, $pattern);
			array_push($this->actions, $actioname);
		}
		public function action($uri = null){
			if($uri === null){
				$uri = explode('?', $_SERVER['REQUEST_URI'])[0]; // GET변수 있으면 떼어내야지...
			}
			$found = false;
			$args = array();
			$func;

			// 정규식 반복. 매칭되는 *을 추출해서 args로
			foreach($this->pattern as $key => $regex){
				if (preg_match($regex, $uri, $founds)) {
					$args = array_slice($founds, 1);
					$func = $this->actions[$key];
					$found = true;
					break;
				}
			}

			// 매칭 없음
			if(!$found){
				$this->error(404);
				return;
			}
			$this->header();

			// 호출

			if($func instanceof Closure){
				call_user_func_array($func, $args);
				return;
			}

			if(sizeof($args)){
				call_user_func_array(array($this,$func), $args);
			}else{
				$this->$func();
			}
		}

		abstract protected function map();

		protected function header(){
			return;
		}
		protected function error($code){
			return;
		}
	}