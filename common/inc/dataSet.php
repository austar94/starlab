<?php
//	기초 데이터 배열 선언
$common							=	array();
$us								=	array();
$common['commonPath']			=	'/home/www/common';
$common['DOCUMENTROOT']			=	$_SERVER['DOCUMENT_ROOT'];

//@header('Content-Type: text/html; charset=UTF-8');
@header('Content-Type: text/html; charset=UTF-8; P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');
@header('Cache-Control: no-store, no-cache, must-revalidate');
@header('Cache-Control: post-check=0, pre-check=0', false);
@header('Pragma: no-cache');
@session_save_path($_SERVER['DOCUMENT_ROOT'] . '/_session');														//	이 옵션에서 LG U+ 전자결제 에러.
@session_cache_limiter('nocache, must-revalidate');																//	캐시가 유지되어 폼값이 보존
@ini_set('session.gc_maxlifetime', 43200);																		//	초 - 세션 만료시간을 12시간으로 설정
@ini_set('session.cache_expire', 43200);																		//	12시간
@ini_set('session.gc_probability', 1);
@ini_set('session.gc_divisor', 100);
if ( !isset($set_time_limit) ) $set_time_limit = 0;
@set_time_limit($set_time_limit);
@session_set_cookie_params(0, '/', $_SERVER['HTTP_HOST']);														//	해당 도메인만 세션 생성
@ini_set('session.cookie_domain', $_SERVER['HTTP_HOST']);														//	세션이 활성화 될 도메인
@ini_set('session.use_trans_sid', 0);
@ini_set('url_rewriter.tags', '');
session_start();																								//	세션 시작

//	메모리 제한 늘리기
ini_set('memory_limit','512M');

if (function_exists("date_default_timezone_set"))
	date_default_timezone_set("Asia/Seoul");

error_reporting(E_ALL & ~E_NOTICE);
//error_reporting(E_ALL);

//	에러 메세지 화면 노출(1 : 활성화, 0 : 비활성화)
ini_set('display_errors', 1);

//	로그 파일에 에러 기록(1 : 활성화, 0 : 비활성화)
@ini_set('log_errors', 1);

//	에러 기록할 로그파일 위치 지정
@ini_set('error_log', $common['DOCUMENTROOT'] . '/saveLog/error/error_' . date('Y-m-d', time()) . '.log');


//	클래스 포함	=========================================================================================================
include_once $common['commonPath'] . '/classes/DBManager.class.php';									//	DB 관리
include_once $common['commonPath'] . '/classes/CommonManager.class.php';								//	공통 관리
include_once $common['commonPath'] . '/classes/MemberManager.class.php';								//	회원 관리
include_once $common['commonPath'] . '/classes/FileManager.class.php';									//	파일 업로드 관리
include_once $common['commonPath'] . '/classes/CorpManager.class.php';									//	업체 관리
include_once $common['commonPath'] . '/classes/BasicSetManager.class.php';								
include_once $common['commonPath'] . '/classes/PointManager.class.php';									//	포인트 관리
include_once $common['commonPath'] . '/classes/GoodsManager.class.php';									//	상품 관리
include_once $common['commonPath'] . '/classes/BoardManager.class.php';									// 게시판 관리
include_once $common['commonPath'] . '/classes/MarketingManager.class.php';								//	SMS/푸시 관련
include_once $common['commonPath'] . '/classes/OrderManager.class.php';									//	주문관련


//$common['DBManager']			=	New DBManager();													//	DB 관리
$common['CommonManager']		=	New CommonManager();												//	공통 관리
$common['MemberManager']		=	New MemberManager();												//	회원 관리
$common['FileManager']			=	New FileManager();													//	파일 업로드 관리
$common['CorpManager']			=	New CorpManager();													//	업체 관리
$common['BasicSetManager']		=	New BasicSetManager();												// 업체 기본 관리
$common['PointManager']			=	New PointManager();													//	포인트 관리
$common['GoodsManager']			=	New GoodsManager();													//	상품 관리
$common['BoardManager']			=	New BoardManager();													// 게시판 관리
$common['MarketingManager']		=	New MarketingManager();												//	SMS/푸시 관련
$common['OrderManager']			=	New OrderManager();													//	주문관련
//	클래스 포함	=========================================================================================================


$us['corpIdx']					=	decrypt($_SESSION['corpIdx']);
$us['userIdx']					=	decrypt($_SESSION['userIdx']);
$us['userCode']					=	decrypt($_SESSION['userCode']);
$us['userID']					=	decrypt($_SESSION['userID']);
$us['userName']					=	decrypt($_SESSION['userName']);
$us['userGubun']				=	decrypt($_SESSION['userGubun']);
$us['connIP']					=	$_SERVER['REMOTE_ADDR'];

$common['wwwURL']				=	$_SERVER['HTTP_HOST'];
$common['wwwPath']				=	$common['DOCUMENTROOT'];
$common['defaultUrl']			=	$common['wwwURL'] . '/uploadFiles';
$common['defaultPath']			=	$common['wwwPath'] . '/uploadFiles';
$common['tempPath']				=	$common['defaultPath'] . '/tempFiles';
$common['uploadVirDir']			=	'/uploadFiles';
$common['dirPermission']		=	0777
$common['userUrl']				=	'http://'.$common['wwwURL'];

$common['sessionID']			=	session_id();
$common['selfPage']				=	$_SERVER['PHP_SELF'];														//	자기의 페이지
$common['pageQueryString']		=	$_SERVER['QUERY_STRING'];
$common['refererPage']			=	$_SERVER['HTTP_REFERER'];													//	이전 페이지
$common['adminPage']			=	'/_Manager';

$refererPage					=	$_SERVER['HTTP_REFERER'];													//	이전 페이지
$selfPage						=	$_SERVER['PHP_SELF'];														//	자기의 페이지
$nowPage						=	$_SERVER['HTTPS'] ? 'https://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI'] : 'http://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI'];					//	현재페이지

defined(	'Heeyam'				)	OR	define(	'Heeyam',				true													);	//	개별페이지 접근불가
?>
