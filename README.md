BPTalk
==============

PHP Parameter URL (?) 정확한 명칭을 모르겠음..
--------------

간단한 서버를 짤때 mod_rewrite로 route하고 매번 explode 하는게 복잡해서 만들었습니다.
BPTalkMap 서브클래스 하나 만들고 invoke 하면 끝!

map()에서 url 패턴과 대상 함수를 지정해주세요. 와일드카드에 들어간 내용들이 순서대로 함수 인자로 넘어갑니다.

WTFPL.