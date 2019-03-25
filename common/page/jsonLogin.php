<?php
// 로그인 여부 및 로그인 유저 권한 확인
// 로그인 플래그값이 없을경우 로그인 여부 확인 
if(!$loginFlag){
	if(!$us['userIdx'] || $us['userType'] != 3){
		session_start();
		session_unset();
		session_destroy();
		$data						=	array(
			'errCd'						=>	-9999,
			'errMsg'					=>	''
		);
		echo json_encode($data);
		exit;
	}
}


//토큰 확인
if(!$_POST['token'] && !$_GET['token']){
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'유효하지 않은 토큰입니다.'
	);
	echo json_encode($data);
	exit;
}
$pathToken						=	$_POST['token']		?	$_POST['token']	:	$_GET['token'];

//현재 사이트의 토큰값
$csrf_token          = $_SESSION['token'][$_SERVER["PHP_SELF"];

if(!$pathToken	||	$pathToken !== $csrf_token){
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'유효하지 않은 토큰입니다.'
	);
	echo json_encode($data);
	exit;
}
