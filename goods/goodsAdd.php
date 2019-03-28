<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/page/head.php'; 
$goodsIdx				=	$_GET['no']		?	allTags($_GET['no'])		:	'';

$goodsName				=	'';						//상품 명
$goodsInfo				=	'';						//상품 안내
$isSale					=	1;						//판매여부
$isOpiton				=	0;						//옵션여부
$isAdditional			=	0;						//추가상품여부
$isDiscount				=	0;						//할인여부 			0:할인없음, 1:원할인, 2:퍼센트할인
$discount				=	'';						//할인양
$isDelivery				=	0;						//배송여부
$deliverySet			=	1;						//배송 설정 1:기본설정, 2:직접설정
$deliveryType			=	1;						//배송 조건			1:무료, 2:유료, 3:금액, 4:수량

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
	$isOpiton			=	$goods['isOpiton'];	
	$isAdditional		=	$goods['isAdditional'];	
	$isDiscount			=	$goods['isDiscount'];
	$discount			=	$goods['discount'];
	$isDelivery			=	$goods['isDelivery'];	
	$deliverySet		=	$goods['deliverySet'];
	$deliveryType		=	$goods['deliveryType'];

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
	<form id="frm">
		<?= //수정 대부 코드저장 ?>
		<input type="hidden" name="goods" value="<?=$goodsIdx?>">
		<?= //수정 대부 코드저장 ?>

		<input type="text" name="goodsName" value="<?=$goodsName?>">

		<?= //판매여부 ?>
		<label><input type="radio" name="isSale" value="1"<?=($isSale == 1) ? ' checked' : ''?>>판매</label>
		<label><input type="radio" name="isSale" value="0"<?=($isSale == 1) ? '' : ' checked'?>>판매 중지</label>
		<?= //판매여부 ?>

		<?= //판매금액 숫자만입력, 3자리 콤마 ?>
		<input type="text" name="goodsPrice" onkeydown="return setPeriodNumberOnKeyDown(event)" onkeyup="setNumberWithCommasKeyUp(this)">
		<?= //판매금액 숫자만입력, 3자리 콤마 ?>
		
		<?= //할인여부 ?>
		<label><input type="radio" name="isDiscount" value="0"<?=($isDiscount == 0) ? ' checked' : ''?>>할인없음</label>
		<label><input type="radio" name="isDiscount" value="1"<?=($isDiscount == 1) ? ' checked' : ''?>>원 할인</label>
		<label><input type="radio" name="isDiscount" value="2"<?=($isDiscount == 2) ? ' checked' : ''?>>퍼센트 할인</label>
		<div class="discount"<?=($isDiscount) ? '' : ' style="display:none"'?>style="display:none">
			<input type="text" name="discount" onkeydown="return setPeriodNumberOnKeyDown(event)" onkeyup="setNumberWithCommasKeyUp(this); setDiscount(this)" value="<?=$discount?>">
		</div>
		<?= //할인여부 ?>

		<?= //배송여부 ?>
		<label><input type="radio" name="isDelivery" value="0"<?=($isDelivery) ? ' checked' : ''?>>배송없음</label>
		<label><input type="radio" name="isDelivery" value="1"<?=($isDelivery) ? '' : ' checked'?>>배송있음</label>

		<?= //배송 기존 설정, 직접설정 여부 ?>
		<div class="deliverySet">
			<label><input type="radio" name="deliverySet" value="1"<?=($deliverySet == 1) ? ' checked' : ''?>>기본설정</label>
			<label><input type="radio" name="deliverySet" value="2"<?=($deliverySet == 2) ? ' checked' : ''?>>직접설정</label>
		</div>
		<?= //배송 기존 설정, 직접설정 여부 ?>

		<div class="deliverForm">
			<label><input type="radio" name="deliveryType" value="1"<?=($deliveryType == 1) ? ' checked' : ''?>>무료배송</label>
			<label><input type="radio" name="deliveryType" value="2"<?=($deliveryType == 2) ? ' checked' : ''?>>고정배송</label>
			<label><input type="radio" name="deliveryType" value="3"<?=($deliveryType == 3) ? ' checked' : ''?>>금액별 무료배송</label>
			<label><input type="radio" name="deliveryType" value="4"<?=($deliveryType == 4) ? ' checked' : ''?>>수량별 무료배송</label>
			<div class="deilveryTypeForm">
				<div class="deliveryType1">

				</div>
			</div>
		</div>
		<?= //배송여부 ?>

		<div id="opBox"></div>
		<div id="addBox"></div>
	</form>

	<script src="/goods/js/goodsAdd.js"></script>
</body>
</html>
