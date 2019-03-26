<?php  
$loginFlag						=	1;
include $_SERVER['DOCUMENT_ROOT'] . "/common/page/top.php";
include $_SERVER['DOCUMENT_ROOT'] . "/common/page/jsonLogin.php";

foreach($_POST as $key=>$post) $_POST[$key] = allTags($post);

if(!$_POST['userID']){
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'아이디를 입력해주시기 바랍니다.'
	);
	echo json_encode($data);
	exit;
}

if(preg_match('/[^a-zA-Z0-9]{6,15}+/', $_POST['userID'])) {
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'아이디를 조건이 맞지 않습니다. (영문소문자/숫자, 6~15자)'
	);
	echo json_encode($data);
	exit;
}

if(!$_POST['userPWD']){
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'비밀번호를 입력해주시기 바랍니다.'
	);
	echo json_encode($data);
	exit;
}

if(preg_match('/^(?=.*[a-zA-Z0-9+])((?=.*\d)|(?=.*\W)).{6,15}$/', $_POST['userPWD'])) {
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'비밀번호 조건이 맞지 않습니다. ((영문 대소문자,숫자,특수문자, 6~15자))'
	);
	echo json_encode($data);
	exit;
}

if(!$_POST['re_userPWD']){
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'비밀번호 확인을 입력해주시기 바랍니다.'
	);
	echo json_encode($data);
	exit;
}

if($_POST['userPW'] !== $_POST['re_userPWD']){
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'비밀번호가 일치하지 않습니다.'
	);
	echo json_encode($data);
	exit;
}

if(!$_POST['userMobile']){
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'핸드폰번호를 입력해주세요.'
	);
	echo json_encode($data);
	exit;
}

if(preg_match('/^\d{3}\d{3,4}\d{4}$/', $_POST['userMobile'])) {
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'잘못된 핸드폰 번호입니다.'
	);
	echo json_encode($data);
	exit;
}

if(!$_POST['userMail']){
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'이메일을 입력해주세요.'
	);
	echo json_encode($data);
	exit;
}

if(preg_match('/^[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_\.]?[0-9a-zA-Z])*\.[a-zA-Z]{2,3}$/i', $_POST['userMail'])) {
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'잘못된 형식의 이메일입니다.'
	);
	echo json_encode($data);
	exit;
}

if(!$_POST['userZip']){
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'우편번호를 입력해주세요.'
	);
	echo json_encode($data);
	exit;
}

if(!$_POST['userAddr2']){
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'상세주소를 입력해주세요.'
	);
	echo json_encode($data);
	exit;
}

//아이디 중복 확인
$search							=	array(
	'userID'						=>	$_POST['userID']
);
$msg							=	$common['MemberManager']->get_userData(1, '', '', $search);
$user							=	$msg->getData();
if($user){
	echo '이미 사용중인 아이디입니다.';
	exit;
}

//핸드폰 중복 확인
$search							=	array(
	'userMobile'					=>	$_POST['userMobile']
);
$msg							=	$common['MemberManager']->get_userData(1, '', '', $search);
$user							=	$msg->getData();
if($user){
	echo '이미 사용중인 핸드폰번호입니다.';
	exit;
}

$data							=	array(
	'userID'						=>	$_POST['userID'],
	'userPWD'						=>	$_POST['userPWD'],
	'userMobile'					=>	$_POST['userMobile'],
	'userMail'						=>	$_POST['userMail']
);
$msg							=	$common['MemberManager']->insert_user($data);
if($msg->isResult()){
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'회원가입을 환영합니다.',
		'url'						=>	'/'
	);
	echo json_encode($data);
	exit;
} else {
	$data						=	array(
		'errCd'						=>	1,
		'errMsg'					=>	'오류가 발생하였습니다. 관리자에게 문의해주시기 바랍니다.'
	);
	echo json_encode($data);
	exit;
}