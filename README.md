BPTalk
==============

소개
--------------
"https://example.com/api/user/*"와 같은 URL에서 *에 해당하는 내용들을 함수의 인자로 만들어줍니다.
간단한 서버를 짤때 mod_rewrite로 route하고 매번 explode 하는게 복잡해서 만들었습니다.

BPTalkMap 서브클래스 하나 만들고 invoke 하면 끝!
map()에서 url 패턴과 대상 함수를 지정해주세요. 와일드카드에 들어간 내용들이 순서대로 함수 인자로 넘어갑니다.

사용법
--------------
example.php를 참고하세요.
```php
<?php
	include "BPTalk/BPTalkMapClass.php";

	// BPTalkMapClass를 상속하세요
	class MainTalk extends BPTalkMapClass {
		// 필수
		function map(){
			// "GET변수를 제외한 Request URI 패턴" => "대상함수"의 형태로 규칙들을 배열로 리턴하세요.
			return array(
				"/api/static" = "staticResult",
				"/api/user/*" => "showUserInfo",
				"/api/user/*/isfriend/*" => "showUserRelationship"
			);
		}
		// map()에서 사용한 함수는 모두 이 클래스 내부에 정의되어야합니다.
		function staticResult(){
			echo "This is static text!";
		}
		function showUserInfo($userId){ // *에 해당하는 내용들이 순서대로 함수의 인자로 넘어갑니다.
			echo getUserInfo($userId);
		}
		function showUserRelationship($uid1, $uid2){
			echo getUserRelationship($uid1, $uid2);
		}

		// 이하는 선택사항입니다.

		// header를 클래스 외부에서 설정하거나 BPTalkMapClass의 header()를 override 할 수 있습니다.
		function header(){
			header("Content-Type: text/json");
		}

		// error를 직접 정의할 수 있습니다.
		function error($code){
			if($code==404){ // 일치하는 패턴이 없으면 error(404);를 호출합니다.
				echo "Pattern Not Found!";
			}
		}
	}

	new MainTalk(); // 인스턴스 생성과 동시에 실행됩니다.

	// 또는 동시 실행을 막을 수 있습니다.

	$talkMap = new MainTalk(false);
	$talkMap -> addMap([패턴],[대상함수]); // 생성자 호출 이후에 패턴추가시..
	$talkMap -> action(); // action이 실행되면 
	// $talkMap -> action("/api/user/1520"); 과 같이 직접 request uri 지정도 가능합니다.
?>
```

저작권
--------------
WTFPL.