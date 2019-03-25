<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/common/page/top.php';
include $_SERVER['DOCUMENT_ROOT'] . '/common/page/pageCheck.php';

//페이지별 token 생성.
//$_SESSION['csrf_token']		=	base64_encode(openssl_random_pseudo_bytes(32));

//  2019-03-25 star 현재 임시로 페이지별 token고정
if(!$_SESSION['token'][$_SERVER["PHP_SELF"]]){
  $_SESSION['token'][$_SERVER["PHP_SELF"]       = base64_encode(openssl_random_pseudo_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
  <title>Title</title>
  <script src="/common/js/jquery-3.1.1.min.js"></script>
  <script src="/common/js/common.js"></script>
</head>
