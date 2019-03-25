<?php  
$loginFlag						=	1;
include $_SERVER['DOCUMENT_ROOT'] . "/common/page/top.php";
include $_SERVER['DOCUMENT_ROOT'] . "/common/page/jsonLogin.php";

if(!$_POST['userID']){
  $data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'아이디를 입력해주시기 바랍니다.'
	);
	echo json_encode($data);
	exit;
}

if(preg_match('/[^a-z0-9]{6,15}+/', $_POST['userID'])) {
    $data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'아이디를 조건이 맞지 않습니다. (영문소문자/숫자, 6~15자)'
	);
	echo json_encode($data);
	exit;
}


//userState		1:정상, 2:정지, 3:
$search							=	array(
	'userID'					  	=>	$_POST['userID'],
	'userState'						=>	array(1,2,3)
);
$msg							  =	$common['MemberManager']->get_userData(1, '', '', $search);
if($msg->getData()){
	$data						  =	array(
		'errCd'						=>	0,
		'errMsg'					=>	'',
		'success'					=>	1
	);
	echo json_encode($data);
	exit;
} else {
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'이미 사용중인 아이디입니다.'
	);
	echo json_encode($data);
	exit;
}
