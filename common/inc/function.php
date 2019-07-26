<?php

//토큰 갱신
function newTOKEN($url){
	$_SESSION['token'][$url]       = base64_encode(openssl_random_pseudo_bytes(32));
	return $_SESSION['token'][$url];
}

//	remove AllTags
function allTags($data)
{
	if ($data) {
		if(is_array($data)) {
			foreach($data as $key => $value){
				$data[$key]			=	strip_tags(addslashes(trim($value)));
			}
			return $data;
		} else {
			return strip_tags(addslashes(trim($data)));
		}
	} else {
		return $data;
	}
}

function allStrip($data)
{
	if ($data) {
		if(is_array($data)) {
			foreach($data as $key => $value){
				$data[$key]			=	stripslashes($value);
			}
			return $data;
		} else {
			return stripslashes($data);
		}
	} else {
		return $data;
	}
}

//	Seed128 + CBC + PKCS5	=============================================
//https://github.com/qnibus/seed128 
include_once $commonPath . '/seed/class.crypto.php';

//	암호화
function encrypt($str) {
	$crypto						=	new Crypto();
	return $crypto->encrypt($str);
}

//	복호화
function decrypt($str) {
	$crypto						=	new Crypto();
	return $crypto->decrypt($str);
}
//	Seed128 + CBC + PKCS5	=============================================


function getPage($recordPerPage, $pnoPerPage, $pno, $totalCount)
{	
	$pnoCount			=	ceil($totalCount / $recordPerPage);
	$temp				=	0;
	$ppno				=	1;
	
	while(1){
		$temp				+=	$pnoPerPage;
		if ($temp >= $pno) break;
		$ppno++;
	}

	$page				=	array();
	$page['pno']		=	$pno;
	$page['ppno']		=	$ppno;
	$page['maxPpno']	=	ceil($pnoCount / $pnoPerPage);
	$page['spno']		=	($ppno - 1) * $pnoPerPage + 1;
	$page['epno']		=	$ppno * $pnoPerPage;
	$page['sno']		=	$totalCount - (($pno - 1) * $recordPerPage);

	if ($page['epno'] > $pnoCount) $page['epno'] = $pnoCount;
	return $page;
}

//paging
function paging($page, $qry)
{
	echo			'<ul>';

	if ($page['ppno'] > 1) {
		echo		'	<li><a href="'.$selfPage.'?pno=1'.$qry.'">First</a></li>';
		echo		'	<li><a href="'.$selfPage.'?pno='.($page['spno'] - 1).$qry.'">Prev</a></li>';
	}

	for ($i = $page['spno']; $i <= $page['epno']; $i++) {
		if($i == $page['pno']) {
			echo	'	<li><a href="javascript:void(0)" class="num active">'.$i.'</a></li>';
		} else {
			echo	'	<li><a href="'.$selfPage.'?pno='.$i.$qry.'" class="num">'.$i.'</a></li>';
		}
	}

	if ($page['ppno'] != $page['maxPpno']) {
		echo		'	<li><a href="'.$selfPage.'?pno='.($page['epno'] + 1).$qry.'">Next</a></li>';
	}

	if ($page['ppno'] < $page['maxPpno']) {
		echo		'	<li><a href="'.$selfPage.'?pno='.$page['maxPpno'].$qry.'">Last</a></li>';
	}

	echo			'</ul>';
}


/**
 * @date			2018-10-30
 * @author			star
 * @details			curl
 * 					
 */
// CURL 함수
function restful_curl($url, $param='', $method='POST', $header='', $timeout=10) {
	$method 		= (strtoupper($method) == 'POST') ? '1' : '0';
	$ch 			= curl_init();
	
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	if(is_array($header) > 0) {
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	}
	curl_setopt($ch, CURLOPT_POST, $method);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
	curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

?>
