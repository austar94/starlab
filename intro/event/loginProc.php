<?php  
$loginFlag						=	1;
include $_SERVER['DOCUMENT_ROOT'] . "/common/page/top.php";
include $_SERVER['DOCUMENT_ROOT'] . "/common/page/jsonLogin.php";
$newToken						=	newTOKEN($_SERVER["HTTP_REFERER"]);

foreach($_POST as $key=>$post) $_POST[$key] = allTags($post);

if(!$_POST['userID']){
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'아이디를 입력해주시기 바랍니다.',
		'token'						=>	$newToken
	);
	echo json_encode($data);
	exit;
}

if(!$_POST['userPWD']){
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'비밀번호를 입력해주시기 바랍니다.',
		'token'						=>	$newToken
	);
	echo json_encode($data);
	exit;
}

$search							=	array(
	'userType'						=>	3,
	'loginType'						=>	1,
	'userID'						=>	$_POST['userID'],
	'userPWD'						=>	$_POST['userPWD']
);
$msg							=	$common['MemberManager']->get_userData(1 ,'', '', $search);
$user							=	$msg->getData();
if($user){
	$user						=	allStrip($user[0]);
	$userIdx					=	$user['userIdx'];					//회원 idx
	$userState					=	$user['userState'];					//회원상태
	$userType					=	$user['userType'];					//1:관리자, 2:직원, 3:회원


	//정상
	if($userState == 1){
		//마지막 로그인시간 갱신
		//로그인 ip저장
		$data					=	array(
			'userIdx'				=>	$userIdx,
			'userID'				=>	$_POST['userID']
		);
		$common['MemberManager']->insert_loginLog($data);

		//세션에 로그인정보 추가
		$_SESSION['userIdx']		=	encrypt($userIdx);
		$_SESSION['userID']			=	encrypt($_POST['userID']);
		$_SESSION['userType']		=	encrypt($userType);

		$data						=	array(
			'errCd'						=>	1,
			'errMsg'					=>	'',
			'url'						=>	'/'
		);
		echo json_encode($data);
		exit;
	} 
	//정지
	else if($userState == 2){
		$data						=	array(
			'errCd'						=>	1,
			'errMsg'					=>	'정지된 아이디입니다. 관리자에게 문의해주시기 바랍니다.',
			'token'						=>	$newToken
		);
		echo json_encode($data);
		exit;
	} 
	//미승인
	else if($userState == 2){
		$data						=	array(
			'errCd'						=>	1,
			'errMsg'					=>	'미승인된 아이디입니다. 관리자에게 확인해주시기 바랍니다.',
			'token'						=>	$newToken
		);
		echo json_encode($data);
		exit;
	} 
	//삭제
	else if($userState == 2){
		$data						=	array(
			'errCd'						=>	1,
			'errMsg'					=>	'삭제된 아이디입니다.',
			'token'						=>	$newToken
		);
		echo json_encode($data);
		exit;
	} 


} else {
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'아이디 혹은 비밀번호를 확인해주시기 바랍니다.',
		'token'						=>	$newToken
	);
	echo json_encode($data);
	exit;
}