<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/page/head.php'; 
$goodsIdx				=	$_GET['no']		?	allTags($_GET['no'])		:	'';

$goodsName				=	'';						//상품 명
$goodsInfo				=	'';						//상품 안내
$isSale					=	1;						//판매여부
$isDelivery				=	0;						//배송여부
$isOpiton				=	0;						//옵션여부
$isAdditional			=	0;						//추가상품여부

$opHeadList				=	'';						//옵션 헤드
$addHeadList			=	'';						//추가상품 헤드
$opList					=	'';						//옵션 리스트
$addList				=	'';						//추가상품 리스트

//-----------------------------------------------------------------------------------------------
//	상품 idx가 존재할경우 상품수정
//-----------------------------------------------------------------------------------------------
if($goodsIdx){
	$msg				=	$common['GoodsManager']->get_goodsList(1, '', $goodsIdx);
	$goods				=	$msg->getData();

	//상품 정보가 존재하지 않을경우
	if(!$goods){
		$common['CommonManager']->goPage('/goods', '잘못된 상품 정보입니다.');
		return;
	}

	$goods				=	allStrip($goods[0]);
	$goodsName			=	$goods['goodsName'];
	$goodsInfo			=	$goods['goodsInfo'];	
	$isSale				=	$goods['isSale'];	
	$isDelivery			=	$goods['isDelivery'];	
	$isOpiton			=	$goods['isOpiton'];	
	$isAdditional		=	$goods['isAdditional'];	

	//옵션이 존재할경우
	if($isOption){
		$search			=	array(
			'isUse'			=>	1,
			'goodsIdx'		=>	$goodsIdx,
			'opType'		=>	1
		);
		$msg			=	$common['GoodsManager']->get_goodsOpList('', '', '', $search);
		$opList			=	$msg->getData();
	}

	//추가상품이 존재할경우
	if($isAdditional){
		$search			=	array(
			'isUse'			=>	1,
			'goodsIdx'		=>	$goodsIdx,
			'opType'		=>	2
		);
		$msg			=	$common['GoodsManager']->get_goodsOpList('', '', '', $search);
		$addList		=	$msg->getData();
	}
}

//-----------------------------------------------------------------------------------------------
//	카테고리정보
//-----------------------------------------------------------------------------------------------
$search					=	array(
	'isUse'					=>	1
);
$msg					=	$common['GoodsManager']->get_cartList('', '', '', $search);
	
?>
<body>
	<div id="goodsBox">
		<input type="hidden" name="goods" <?=$goodsIdx?>>
		<div id="opBox"></div>
		<div id="addBox"></div>
	</div>
</body>
</html>
