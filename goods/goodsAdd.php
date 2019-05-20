<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/page/head.php'; 
$goodsIdx						=	allTags($_GET['no'])		?	allTags($_GET['no'])		:	'';
$type							=	allTags($_GET['type'])		?	allTags($_GET['type'])		:	1;		//1:등록, 2:수정, 3:복제

//수정이나 복제일때 goodsIdx 없는경우
if(($type == 2 || $type == 3) && !$goodsIdx){
	$common['CommonManager']->goPage('/goods', '잘못된 접근입니다.');
	exit;
} 
//일단 등록인데 goodsIdx 있는경우
else if($type == 1 && $goodsIdx){
	$common['CommonManager']->goPage('/goods/', '잘못된 접근입니다.');
	exit;
}

$goodsName						=	'';							//상품 명
$category1						=	'';							//카테고리 코드
$goodsNew						=	0;							//상품태그 new 사용여부
$goodsHot						=	0;							//상품태그 hot 사용여부
$colorSet1						=	'';							//색상표시 rgb코드
$colorSet2						=	'';							//색상표시 rgb코드
$colorSet3						=	'';							//색상표시 rgb코드
$colorSet4						=	'';							//색상표시 rgb코드
$colorSet5						=	'';							//색상표시 rgb코드
$goodsPrice						=	0;							//상품 금액

$saleType						=	0;							//할인유형 0:없음, 1:퍼센트할인, 2:원할인
$salePerRate					=	'';							//할인 사용시 퍼센트 할인 금액
$saleStaticRate					=	'';							//할인 사용시 원 할인 금액
$beforePrice					=	'';							//할인 전금액
$isSale							=	1;							//전시여부
$isSoldOut						=	0;							//품절여부

$goodsStock						=	'';							//상품 수량
$goodsImg1						=	'';							//상품 이미지
$goodsImg2						=	'';							//상품 이미지
$goodsImg3						=	'';							//상품 이미지
$goodsImg4						=	'';							//상품 이미지
$goodsImg5						=	'';							//상품 이미지
$goodsSimple					=	'';							//상품 간단설명
$goodsInfoW						=	'';							//상품 상세설명 (웹)
$goodsInfoA						=	'';							//상품 상세설명 (모바일)
$opType							=	0;							//옵션타입 0:사용안함, 1:일바옵션, 2:재고옵션
$addType						=	0;							//추가상품 사용여부 0:사용안함, 1:사용
$opHManage1						=	'';							//옵션 사용시 옵션명
$opHManage2						=	'';							//옵션 사용시 옵션명
$opHManage3						=	'';							//옵션 사용시 옵션명
$opBManage1						=	'';							//옵션 사용시 옵션값
$opBManage2						=	'';							//옵션 사용시 옵션값
$opBManage3						=	'';							//옵션 사용시 옵션값
$addHManage1					=	'';							//추가상품 사용시 추가옵션 명
$addHManage2					=	'';							//추가상품 사용시 추가옵션 명
$addHManage3					=	'';							//추가상품 사용시 추가옵션 명
$addBManage1					=	'';							//추가옵션 사용시 추가옵션 값
$addBManage2					=	'';							//추가옵션 사용시 추가옵션 값
$addBManage3					=	'';							//추가옵션 사용시 추가옵션 값
$manageMsg						=	'';							//관리자 메모
$isDelivery						=	0;							//배송 사용여부
$deliverySet					=	2;							//배송 사용시 설정 1:기본설정(관리자단의 기본값을 따름), 2:직접 설정
$goodsImgInfo					=	'';							//상품 이미지


$deliveryType					=	1;							//배송 유형 1:무료배송, 2:고정배송, 3:금액별 무료배송, 4:수량별 무료배송, 4:괌 직구
$deliveryTypeQty3				=	'';							//금액별 배송시 조건
$deliveryTypeQty4				=	'';							//수량별 배송시 조건
$deliveryOrderType				=	1;							//배송비 결제 조건 1:선결제, 2:착불, 3:구매자 선택
$isBundle						=	0;							//묶음상품 여부 0:미사용, 1:사용
$bundlePrice					=	'';							//묶음상품 사용시 조건
$deliveryPrice2					=	'';							//고정배송 배송비
$deliveryPrice3					=	'';							//금액별 배송 배송비
$deliveryPrice4					=	'';							//수량별 배송 배송비
$isRegionOption					=	0;							//지역별 배송 사용여부 0:사용안함, 1:배송권역방식
$regionOptionType				=	'';							//1:제 2권역, 2:제 3권역
$isRegionPrice1					=	'';							//제 2권역 선택시 제주/도서산간지역 추가 배송비
$isRegionPrice2					=	'';							//제 3권역 선택시 제주지역 추가 배송비
$isRegionPrice3					=	'';							//제 3권역 선택시 도서산간지역 추가 배송비

$opHCount						=	3;
$opOtion1						=	'';							//옵션 사용시 옵션값
$opOtion2						=	'';							//옵션 사용시 옵션값
$addOption						=	'';							//추가상품 사용시 추가상품값

//-----------------------------------------------------------------------------------------------
//	배송,교환,환불 기본 폼
//-----------------------------------------------------------------------------------------------
$msg							=	$common['CorpManager']->get_corpData('', '', $us['corpIdx']);
$basic 							=	$msg->getData();
$basic							=	allStrip($basic[0]);
$returnInfo						=	$basic['returnForm'];				//횐불 안내


//-----------------------------------------------------------------------------------------------
//	상품 idx가 존재할경우 상품수정
//-----------------------------------------------------------------------------------------------
if($goodsIdx){
	$msg						=	$common['GoodsManager']->get_goodsList(1, '', $goodsIdx);
	$goods						=	$msg->getData();

	//상품 정보가 존재하지 않을경우
	if(!$goods){
		$common['CommonManager']->goPage('/goods', '잘못된 상품 정보입니다.');
		return;
	}

	$goods						=	allStrip($goods[0]);
	$goodsName					=	$goods['goodsName'];				//상품 명
	$category1					=	$goods['category1'];				//상품 카테고리
	$category2					=	$goods['category2'];				//상품 카테고리
	$category3					=	$goods['category3'];				//상품 카테고리
	$goodsNew					=	$goods['goodsNew'];					//new태그
	$goodsHot					=	$goods['goodsHot'];					//hot태그
	$colorSet1					=	$goods['colorSet1'];				//색상표시1
	$colorSet2					=	$goods['colorSet2'];				//색상표시2
	$colorSet3					=	$goods['colorSet3'];				//색상표시3
	$colorSet4					=	$goods['colorSet4'];				//색상표시4
	$colorSet5					=	$goods['colorSet5'];				//색상표시5
	$goodsPrice					=	$goods['goodsPrice'];				//상품 가격

	$saleType					=	$goods['saleType'];					//할인유형 0:없음, 1:퍼센트할인, 2:원할인
	$salePerRate				=	$goods['salePerRate'];				//할인 사용시 퍼센트 할인 금액
	$saleStaticRate				=	$goods['saleStaticRate'];			//할인 사용시 원 할인 금액
	$beforePrice				=	$goods['beforePrice'];				//할인 전 금액
	$isSale						=	$goods['isSale'];					//전시여부
	$isSoldOut					=	$goods['isSoldOut'];				//품절여부

	$goodsStock					=	$goods['goodsStock'];				//상품 재고수량
	$goodsImg1					=	$goods['goodsImg1'];				//상품 이미지1
	$goodsImg2					=	$goods['goodsImg2'];				//상품 이미지2
	$goodsImg3					=	$goods['goodsImg3'];				//상품 이미지3
	$goodsImg4					=	$goods['goodsImg4'];				//상품 이미지4
	$goodsImg5					=	$goods['goodsImg5'];				//상품 이미지5
	$goodsSimple				=	$goods['goodsSimple'];				//상품 간단소개
	$goodsInfoW					=	html_entity_decode($goods['goodsInfoW']);				//상품상세정보 웹
	$goodsInfoA					=	html_entity_decode($goods['goodsInfoA']);				//상품상세정보 앱
	$opType						=	$goods['opType'];					//상품 옵션설정 1:일반옵션, 2:재고옵션, 0:사용안함
	$addType					=	$goods['addType'];					//추가상품 옵션 사용여부
	$opHManage1					=	$goods['opHManage1'];				//상품 옵션명1
	$opHManage2					=	$goods['opHManage2'];				//상품 옵션명2
	$opHManage3					=	$goods['opHManage3'];				//상품 옵션명3
	$opBManage1					=	$goods['opBManage1'];				//상품 옵션값1
	$opBManage2					=	$goods['opBManage2'];				//상품 옵션값2
	$opBManage3					=	$goods['opBManage3'];				//상품 옵션값3
	$addHManage1				=	$goods['addHManage1'];				//추가상품 옵션명1
	$addHManage2				=	$goods['addHManage2'];				//추가상품 옵션명2
	$addHManage3				=	$goods['addHManage3'];				//추가상품 옵션명3
	$addBManage1				=	$goods['addBManage1'];				//추가상품 옵션명1
	$addBManage2				=	$goods['addBManage2'];				//추가상품 옵션명2
	$addBManage3				=	$goods['addBManage3'];				//추가상품 옵션명3
	$manageMsg					=	$goods['manageMsg'];				//관리자 메시지
	$isDelivery					=	$goods['isDelivery'];				//배송기능 사용여부
	$deliverySet				=	$goods['deliverySet'];				//배송 설정 1:기본, 2:개별
	$returnInfo					=	$goods['returnInfo'];				//배송/반품/환불 안내
	$goodsImgInfo				=	$goods['goodsImgInfo'];				//상품이미지

	if($deliverySet == 1){
		$deliveryType			=	$basic['deliveryType'];				//1:무료배송, 2:고정배송, 3:금액별 무료배송, 4:수량별 무료배송
		$deliveryPrice			=	$basic['deliveryPrice'];			//배송비
		$deliveryTypeQty3		=	$basic['deliveryTypeQty3'];			//금액별/수량별 무료배송 갯수
		$deliveryTypeQty4		=	$basic['deliveryTypeQty4'];			//금액별/수량별 무료배송 갯수
		$deliveryOrderType		=	$basic['deliveryOrderType'];		//1:선결제, 2:착불. 3:구매자 선택
		$isBundle				=	$basic['isBundle'];					//묶음 상품 사용여부
		$bundlePrice			=	$basic['bundlePrice'];				//묶음배송 무료 조건
		$deliveryPrice2			=	$basic['deliveryPrice2'];			//해당 금액 이상일경우 묶음상품 모두 무료
		$deliveryPrice3			=	$basic['deliveryPrice3'];			//해당 금액 이상일경우 묶음상품 모두 무료
		$deliveryPrice4			=	$basic['deliveryPrice4'];			//해당 금액 이상일경우 묶음상품 모두 무료
		$isRegionOption			=	$basic['isRegionOption'];			//지역별배송 배송권역 사용여부
		$regionOptionType		=	$basic['regionOptionType'];			//지역배송 옵션 1:2권역, 2:3권역
		$isRegionPrice1			=	$basic['isRegionPrice1'];			//제주/도서산간 지역 추가 배송비 
		$isRegionPrice2			=	$basic['isRegionPrice2'];			//제주지역 추가 배송비 
		$isRegionPrice3			=	$basic['isRegionPrice3'];			//도서산간지역 추가 배송비
	} else {
		$deliveryType			=	$goods['deliveryType'];				//1:무료배송, 2:고정배송, 3:금액별 무료배송, 4:수량별 무료배송
		$deliveryTypeQty3		=	$goods['deliveryTypeQty3'];			//금액별/수량별 무료배송 갯수
		$deliveryTypeQty4		=	$goods['deliveryTypeQty4'];			//금액별/수량별 무료배송 갯수
		$deliveryOrderType		=	$goods['deliveryOrderType'];		//1:선결제, 2:착불. 3:구매자 선택
		$isBundle				=	$goods['isBundle'];					//묶음 상품 사용여부
		$bundlePrice			=	$goods['bundlePrice'];				//1:선결제, 2:착불. 3:구매자 선택
		$deliveryPrice2			=	$goods['deliveryPrice2'];			//해당 금액 이상일경우 묶음상품 모두 무료
		$deliveryPrice3			=	$goods['deliveryPrice3'];			//해당 금액 이상일경우 묶음상품 모두 무료
		$deliveryPrice4			=	$goods['deliveryPrice4'];			//해당 금액 이상일경우 묶음상품 모두 무료
		$isRegionOption			=	$goods['isRegionOption'];			//지역별배송 배송권역 사용여부
		$regionOptionType		=	$goods['regionOptionType'];			//지역배송 옵션 1:2권역, 2:3권역
		$isRegionPrice1			=	$goods['isRegionPrice1'];			//제주/도서산간 지역 추가 배송비 
		$isRegionPrice2			=	$goods['isRegionPrice2'];			//제주지역 추가 배송비 
		$isRegionPrice3			=	$goods['isRegionPrice3'];			//도서산간지역 추가 배송비
	}

	//옵션을 사용할경우
	if($opType){
		//----------------------------------------------------------
		//	상품 옵션1 출력
		//----------------------------------------------------------
		$search						=	array(
			'isUse'						=>	1,
			'opType'					=>	1,
			'goodsIdx'					=>	$goodsIdx
		);
		$msg						=	$common['GoodsManager']->get_godosOpList('', '', '', $search);
		$opOtion1					=	$msg->getData();

		//----------------------------------------------------------
		//	상품 옵션2 출력
		//----------------------------------------------------------
		$search						=	array(
			'isUse'						=>	1,
			'opType'					=>	2,
			'goodsIdx'					=>	$goodsIdx
		);
		$msg						=	$common['GoodsManager']->get_godosOpList('', '', '', $search);
		$opOtion2					=	$msg->getData();

		$opHCount					=	0;
		if($opOtion2){
			$op						=	allStrip($opOtion2[0]);
			$opHead1				=	$op['opHead1'];
			$opHead2				=	$op['opHead2'];
			$opHead3				=	$op['opHead3'];

			if($opHead3)			$opHCount		=	3;
			else if($opHead2)		$opHCount		=	2;
			else if($opHead1)		$opHCount		=	1;
		}
	}

	//추가옵션을 사용할경우
	if($addType){
		//----------------------------------------------------------
		//	상품 추가상품 옵션 출력
		//----------------------------------------------------------
		$search						=	array(
			'isUse'						=>	1,
			'opType'					=>	3,
			'goodsIdx'					=>	$goodsIdx
		);
		$msg						=	$common['GoodsManager']->get_godosOpList('', '', '', $search);
		$addOption					=	$msg->getData();
	}
}

//-----------------------------------------------------------------------------------------------
//	카테고리정보
//-----------------------------------------------------------------------------------------------
$search					=	array(
	'cateLevel'				=>	1,
	'isUse'					=>	1
);
$msg					=	$common['GoodsManager']->get_cateList('', '', '', $search);
$cateList				=	$msg->getData();
if(!$cateList){
	$common['CommonManager']->goPage('/goods/category', '등록된 카테고리 정보가 없습니다. \n카테고리를 먼저 등록해주시기 바랍니다.');
	exit;
}

$message					=	($type == 2 && $goodsIdx)	?	'수정'	:	'등록';

?>
<body>
	<form id="frm">
		<input type="hidden" name="token" value="<?=$_SESSION['token'][$nowPage]?>">
		<input type="hidden" name="type" value="<?=$type?>">
		<input type="hidden" name="goods" value="<?=$goodsIdx?>">
		<input type="hidden" name="isDel_1" id="isDel_1" value='0'>
		<input type="hidden" name="isDel_2" id="isDel_2" value='0'>
		<input type="hidden" name="isDel_3" id="isDel_3" value='0'>
		<input type="hidden" name="isDel_4" id="isDel_4" value='0'>
		<input type="hidden" name="isDel_5" id="isDel_5" value='0'>
		<input type="hidden" name="oldImg1" id="oldImg1" value='<?=$goodsImg1?>'>
		<input type="hidden" name="oldImg2" id="oldImg2" value='<?=$goodsImg2?>'>
		<input type="hidden" name="oldImg3" id="oldImg3" value='<?=$goodsImg3?>'>
		<input type="hidden" name="oldImg4" id="oldImg4" value='<?=$goodsImg4?>'>
		<input type="hidden" name="oldImg5" id="oldImg5" value='<?=$goodsImg5?>'>

		<?=	//카테고리?>
		<select class="category" name="category1" onchange="seleteCategory(this)">
			<option value="">1차카테고리</option>
			<?php
			for($i = 0; $i < sizeof($cateList); $i++){
				$rs					=	allStrip($cateList[$i]);

				$cateIdx			=	$rs['cateIdx'];
				$cateLevel			=	$rs['cateLevel'];
				$cateName			=	$rs['cateName'];
			}
			?>
			<option value="<?=$cateIdx?>"><?=$cateName?></option>
			<?php }?>
		</select>

		<input type="text" name="goodsName" value="<?=$goodsName?>">

		<?= //판매여부 ?>
		<label><input type="radio" name="isSale" value="1"<?=$isSale ? ' checked' : ''?>>판매</label>
		<label><input type="radio" name="isSale" value="0"<?=$isSale ? '' : ' checked'?>>판매 중지</label>
		<?= //판매여부 ?>

		<?= //품절여부 ?>
		<label><input type="radio" name="isSoldOut" value="1"<?=$isSoldOut ? ' checked' : ''?>>판매</label>
		<label><input type="radio" name="isSoldOut" value="0"<?=$isSoldOut ? '' : ' checked'?>>판매 중지</label>
		<?= //품절여부 ?>

		<?= //판매금액 숫자만입력, 3자리 콤마 ?>
		<input type="text" name="goodsPrice" onkeydown="return setPeriodNumberOnKeyDown(event)" onkeyup="setNumberWithCommasKeyUp(this)">
		<?= //판매금액 숫자만입력, 3자리 콤마 ?>
		
		<?= //할인여부 ?>
		<label><input type="radio" name="isDiscount" value="0"<?=($isDiscount == 0) ? ' checked' : ''?>>할인없음</label>
		<label><input type="radio" name="isDiscount" value="1"<?=($isDiscount == 1) ? ' checked' : ''?>>원 할인</label>
		<label><input type="radio" name="isDiscount" value="2"<?=($isDiscount == 2) ? ' checked' : ''?>>퍼센트 할인</label>
		<div class="discount"<?=($isDiscount) ? '' : ' style="display:none"'?>style="display:none">
			<input type="text" name="discount" onkeydown="return setPeriodNumberOnKeyDown(event)" onkeyup="setNumberWithCommasKeyUp(this);" onchange="setDiscount(this)" value="<?=$discount?>">
			<input type="text" name="showDiscountPrice" value="" readonly>
		</div>

		<?= //할인여부 ?>

		<?= //배송여부 ?>
		<label><input type="radio" name="isDelivery" value="0"<?=($isDelivery) ? ' checked' : ''?>>배송없음</label>
		<label><input type="radio" name="isDelivery" value="1"<?=($isDelivery) ? '' : ' checked'?>>배송있음</label>

		<?= //배송 기존 설정, 직접설정 여부 ?>
		<label><input type="radio" name="deliverySet" value="1"<?=($deliverySet == 1) ? ' checked' : ''?>>기본설정</label>
		<label><input type="radio" name="deliverySet" value="2"<?=($deliverySet == 2) ? ' checked' : ''?>>직접설정</label>
		<?= //배송 기존 설정, 직접설정 여부 ?>

		<label><input type="radio" name="deliveryType" value="1"<?=($deliveryType == 1) ? ' checked' : ''?>>무료배송</label>
		<label><input type="radio" name="deliveryType" value="2"<?=($deliveryType == 2) ? ' checked' : ''?>>고정배송</label>
		<label><input type="radio" name="deliveryType" value="3"<?=($deliveryType == 3) ? ' checked' : ''?>>금액별 무료배송</label>
		<label><input type="radio" name="deliveryType" value="4"<?=($deliveryType == 4) ? ' checked' : ''?>>수량별 무료배송</label>

		<input type="text" name="deliveryCondition" value="">

		<?= //배송여부 ?>

		<div id="opBox"></div>
		<div id="addBox"></div>
	</form>

	<script src="/goods/js/goodsAdd.js"></script>
</body>
</html>
