<?php  
include $_SERVER['DOCUMENT_ROOT'] . "/common/page/top.php";
include $_SERVER['DOCUMENT_ROOT'] . "/common/page/jsonLogin.php";
$newToken				=	newTOKEN($_SERVER["HTTP_REFERER"]);
foreach($_POST as $key=>$post) $_POST[$key] = allTags($post);

if(!$_POST['cate']){
	$data				=	array(
		'errCd'				=>	1,
		'errMsg'			=>	'잘못된 접근입니다.',
		'token'				=>	$newToken
	);
	echo json_encode($data);
	exit;
}


$msg					=	$common['GoodsManager']->get_cateList(1, '', $_POST['cate']);
$cate					=	$msg->getData();
if(!$cate){
	$data				=	array(
		'errCd'				=>	1,
		'errMsg'			=>	'잘못된 카테고리 정보입니다.',
		'token'				=>	$newToken
	);
	echo json_encode($data);
	exit;
}
$cate					=	allStrip($cate[0]);
$isUse					=	$cate['isUse'];
if(!$isUse){
	$data				=	array(
		'errCd'				=>	1,
		'errMsg'			=>	'삭제된 카테고리 입니다.',
		'token'				=>	$newToken
	);
	echo json_encode($data);
	exit;
}

$search					=	array(
	'pCateIdx'				=>	$_POST['cate'],
	'isUse'					=>	1
);
$msg					=	$common['GoodsManager']->get_cateList('', '', '', $search);
$cateList				=	$msg->getData();


if($cateList){
	$data				=	array(
		'errCd'				=>	1,
		'errMsg'			=>	'',
		'token'				=>	$newToken,
		'list'				=>	$cateList
	);
	echo json_encode($data);
	exit;
} 
//더이상 하위 카테고리가 없을경우 멈춤
else {
	$data				=	array(
		'errCd'				=>	1,
		'errMsg'			=>	'',
		'token'				=>	$newToken,
		'list'				=>	''
	);
	echo json_encode($data);
	exit;
}