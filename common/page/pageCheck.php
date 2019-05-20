<?php 

//로그인하지 않아도 접근 가능한 페이지 정리
$authorFlag			=	1;
$authorizedPage		=	[
	//'/index.php',
	//'/goods/index.php'
];

//접근가능 페이지가 존재할경우
if($authorizedPage){
	for($i = 0; $i < sizeof($authorizedPage); $i++){
		//접근제한 페이지에 해당하는 페이지일경우 접근플래그 변동
		if($selfPage == $authorizedPage[$i]){
			$authorFlag			=	0;
		}
	}
}

//로그인이 필요한 페이지인경우 유저 정보 확인
if($authorFlag){
	if(!$us['userIdx'] || $us['userType'] != 3){
		unset($_SESSION['userIdx']);
		unset($_SESSION['userID']);
		unset($_SESSION['userType']);
		unset($_SESSION['userCode']);

		//로그인하기 이전 페이지 정보를 함께전송
		$common['CommonManager']->goPage('/intro?url=' . $selfPage, '로그인 필요합니다.', '');
	}
}
