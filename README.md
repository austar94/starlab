# starlab

php기본틀


## **로그인**


[로그인](https://github.com/austar94/starlab/blob/master/intro/index.php),
[로그인_js](https://github.com/austar94/starlab/blob/master/intro/js/index.js)



## **회원가입**


[회원가입_](https://github.com/austar94/starlab/blob/master/intro/join.php),
[회원가입_js](https://github.com/austar94/starlab/blob/master/intro/js/join.js),
[회원가입_php](https://github.com/austar94/starlab/blob/master/intro/event/join_joinProc.php),
[회원가입_sql](https://github.com/austar94/starlab/blob/master/common/classes/MemberManager.class.php#L36)

  -회원가입 정규식
  
    -아이디 - 영문 대소문자,숫자 6-15글자   (/^[a-zA-Z0-9+]{6,15}$/)
  
    -비밀번호 - 영문 대소문자, 숫자, 특수문자 6-15자    (/^(?=.*[a-zA-Z0-9+])((?=.*\d)|(?=.*\W)).{6,15}$/;)
  
    -핸드폰 -10,11글자    (/^\d{3}\d{3,4}\d{4}$/)
  
    -이메일 -   (/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/)
    
  -회원가입시 포인트 지급
  


## **상품등록, 수정**


[상품등록](https://github.com/austar94/starlab/blob/master/goods/goodsAdd.php)
[상품등록_js](https://github.com/austar94/starlab/blob/master/goods/js/goodsAdd.js)

  -n차 카테

  -단계별옵션, 개별옵션

  -추가상품

  -배송옵션

