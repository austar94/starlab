<?php include $_SERVER['DOCUMENT_ROOT'] . '/common/page/head.php'; 

$searchType				=	allTags($_GET['searchType']);
$searchWord				=	allTags($_GET['searchWord']);

//상품 리스트


$recordPerPage			=	10;																		//	한 페이지당 최대 게시글 개수.
$pnoPerPage				=	10;																		//	한 페이지당 최대 페이지번호 개수.
$pno					=	allTags($_GET['pno']) == '' ? 1 : (int)allTags($_GET['pno']);			//	페이지번호.
$temp					=	($pno * $recordPerPage) - $recordPerPage;

$search					=	array(
	'searchType'			=>	$searchType,
	'searchWord'			=>	$searchWord,
	'goodsState'			=>	1,							//판매상태
	'isSale'				=>	1,							//판매여부
	'isUse'					=>	1							//삭제여부
);
$msg					=	$common['GoodsManager']->get_goodsList($temp, $recordPerPage, '', $search);
$list					=	$msg->getData();
$totalCount				=	$msg->getMessage();
$page					=	getPage($recordPerPage, $pnoPerPage, $pno, $totalCount);
$qry					=	'&searchType=' . $searchType . '&searchWord=' . $searchWord;

?>
<body>
	<div class="searchForm">
		<form id="frm">
			<select name="searchType">
				<option value="1"<?=($searchType == 1) ? ' selected' : ''?>>상품명</option>
			</select>
			<input type="text" name="searchWord" value="<?=$searchWord?>">
		</form>
	<div>
	<?php 
	if($list){
		for($i = 0; $i < sizeof($list); $i++){
			$rs					=	allStrip($list[$i]);

			$goodsName			=	$rs['goodsName'];				//상품 명
			$goodsIdx			=	$rs['goodsIdx'];				//상품 idx
			$goodsImg1			=	$rs['goodsImg1'];				//상품 이미지
			$goodsPrice			=	$rs['goodsPrice'];				//판매가격
			$isDiscount			=	$rs['isDiscount'];				//할인여부
			$discount			=	$rs['discount'];				//할인 양

			//원 할인
			if($isDiscount == 1){
				$goodsPrice		=	$goodsPrice - $discount;
			} 
			//퍼센트 할인
			else if($isDiscount == 2){
				$goodsPrice		=	$goodsPrice / $discount;
			}
	?>
	<div class="goodsBox" onclick="/goods/goodsInfo?no=<?=$goodsIdx?>">
		<div><?=$goodsName?></div>
		<div><?=number_format($goodsPrice)?></div>
		<div><img src="<?=$goodsImg1?>"></div>
	</div>
	<?php }
	} else {?>
	<div class="goodsBox">
		<div>상품 내역이 없습니다.</div>
	</div>
	<?php }?>
</body>
</html>
