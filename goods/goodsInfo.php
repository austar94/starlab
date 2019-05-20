<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/page/head.php'; 
$goodsIdx				=	allStrip($_GET['no']);
if(!$godosIdx){
	$common['CommonManager']->goPage('/goods', '잘못된 접근입니다.');
	exit;
}

//삭제되지않은 상품
$search					=	array(
	'isUse'					=>	1
);
$msg					=	$common['GoodsManager']->get_goodsList(1, '', $goodsIdx, $search);
if(!$msg->getData()){
	$common['CommonManager']->goPage('/goods', '잘못된 상품정보입니다.');
	exit;
}
$goods					=	$msg->getData();
$goods					=	allStrip($goods[0]);
$goodsName				=	$goods['goodsName'];				//상품 명
$goodsIdx				=	$goods['goodsIdx'];					//상품 idx
$goodsImg1				=	$goods['goodsImg1'];				//상품 이미지
$goodsImg2				=	$goods['goodsImg2'];				//상품 이미지
$goodsImg3				=	$goods['goodsImg3'];				//상품 이미지
$goodsImg4				=	$goods['goodsImg4'];				//상품 이미지
$goodsImg5				=	$goods['goodsImg5'];				//상품 이미지
$goodsPrice				=	$goods['goodsPrice'];				//판매가격
$isDiscount				=	$goods['isDiscount'];				//할인여부
$discount				=	$goods['discount'];					//할인 양

?>
<body>

</body>
</html>
