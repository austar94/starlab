<?php
class GoodsManager
{
	private $dbm;

	public function __construct()
	{
		$this->dbm 				= 	new DBManager();
	}

	//상품리스트
	public function get_goodsList($pageNo = '', $recordPerPage = '', $goodsCode = '', $data = array()){
		global $us;
		$values					=	array();
		$joinQ					=	'';
		$where0					=	'';
		$LIMIT					=	'';

		$category1				=	$data['category1'];							//카테고리
		$isUse					=	$data['isUse'];								//삭제여부
		$order					=	$data['order'];								//정렬
		$searchType				=	$data['searchType'];						//검색
		$searchWord				=	$data['searchWord'];						//검색
		$minPrice				=	$data['minPrice'];							//상품가격 범위검색
		$maxPrice				=	$data['maxPrice'];							//상품가격 범위검색
		$minStock				=	$data['minStock'];							//상품재고 범위검색
		$maxStock				=	$data['maxStock'];							//상품재고 범위검색
		$isBundle				=	$data['isBundle'];							//묶음상품여부
		$isSale					=	$data['isSale'];							//판매여부
		$stockOut				=	$data['stockOut'];							//품절여부
		$dateS					=	$data['dateS'];								//삼품등록일 범위검색
		$dateE					=	$data['dateE'];								//삼품등록일 범위검색
		$join					=	$data['join'];								//테이블 조인
		$search					=	$data['search'];							//검색 조건
		$hint					=	$data['hint'];								//힌트 부여
		$count					=	$data['count'];
		$goodsNew				=	$data['goodsNew'];
		$goodsHot				=	$data['goodsHot'];
		$group					=	$data['group'];
		
		//-----------------------------------------------------------------------------------------------
		//	SELECT 

		if($count){
			$countQ				=	" COUNT($count) AS count,";
		}

		//-----------------------------------------------------------------------------------------------
		//	WHERE

		if($goodsCode){
			$where0				.=	" AND gl.goodsCode = ?";
			$values[]			=	$goodsCode;
		}

		if($category1){
			$where0				.=	" AND gl.category1 = ?";
			$values[]			=	$category1;
		}

		if($isUse){
			$where0				.=	" AND gl.isUse = ?";
			$values[]			=	$isUse;
		}

		if($searchWord){
			if($searchType == 1){
				$where0			.=	" AND gl.goodsName LIKE ?";
				$values[]		=	'%'.$searchWord.'%';
			} else if($searchType == 2){
				$where0			.=	" AND gl.godosCode LIKE ?";
				$values[]		=	'%'.$searchWord.'%';
			} else if($searchType == 3){
				$where0			.=	" AND cl.cateName LIKE ?";
				$values[]		=	'%'.$searchWord.'%';
				$join			=	1;
			}
		}

		if($minPrice){
			$where0				.=	" AND gl.goodsPrice >= ?";
			$values[]			=	$minPrice;
		}
		if($maxPrice){
			$where0				.=	" AND gl.goodsPrice <= ?";
			$values[]			=	$maxPrice;
		}
		if($minStock){
			$where0				.=	" AND gl.minStock >= ?";
			$values[]			=	$minStock;
		}
		if($maxStock){
			$where0				.=	" AND gl.maxStock <= ?";
			$values[]			=	$maxStock;
		}
		

		if($search == 1){
			if($isBundle == 1 || $isBundle == '0'){
				$where0			.=	" AND gl.isBundle = ?";
				$values[]		=	$isBundle;
			}
			if($isSale == 1 || $isSale == '0'){
				$where0			.=	" AND gl.isSale = ?";
				$values[]		=	$isSale;
			}

			//품절 여부
			if($stockOut == 1){
				$where0			.=	" AND gl.goodsStock = 0";
			} else if($stockOut == '0'){
				$where0			.=	" AND gl.goodsStock != 0";
			}
		}

		if($dateS){
			$where0				.=	" AND gl.regDate >= ?";
			$values[]			=	$dateS . ' 00:00:00';
		}
		if($dateE){
			$where0				.=	" AND gl.regDate <= ?";
			$values[]			=	$dateE . ' 23:59:59';
		}

		if($goodsNew){
			$where0				.=	" AND gl.goodsNew = ?";
			$values[]			=	$goodsNew;
		}

		if($goodsHot){
			$where0				.=	" AND gl.goodsHot = ?";
			$values[]			=	$goodsHot;
		}


		$orderBy				=	$order				?	" ORDER BY $order"					:	'';

		$groupBy				=	$group				?	" GROUP BY $group"					:	'';

		if($join){
			if($join == 1){
				$joinQ			.=	" INNER JOIN tbl_categoryList AS cl ON (gl.category1 = cl.cateCode)";
			}
		}

		$bindValue				=	$values;
		if($pageNo || $recordPerPage){
			if(!$recordPerPage){
				$LIMIT			=	"LIMIT ?";
				$values[]		=	$pageNo;
			} else {
				$LIMIT			=	"LIMIT ?, ?";
				$values[]		=	$pageNo;
				$values[]		=	$recordPerPage;
			}
		}

		$SQL					=	"SELECT $countQ gl.goodsIdx, gl.addType, gl.isSale, gl.isUse, gl.goodsCode, gl.goodsSeq, gl.goodsNew, gl.goodsHot, gl.category1, gl.goodsName, gl.opType, gl.opHManage1, gl.opHManage2, gl.opHManage3, gl.opBManage1, gl.opBManage2, gl.opBManage3, gl.addHManage1, gl.addHManage2, gl.addHManage3, gl.addBManage1, gl.addBManage2, gl.addBManage3, gl.manageMsg, gl.goodsImg1, gl.goodsImg2, gl.goodsImg3, gl.goodsImg4, gl.goodsImg5, gl.goodsPrice, gl.goodsStock, gl.colorSet1, gl.colorSet2, gl.colorSet3, gl.colorSet4, gl.colorSet5, gl.goodsSimple, gl.goodsInfoW, gl.goodsInfoA, gl.deliverySet, gl.deliveryType, gl.deliveryTypeQty3, gl.deliveryTypeQty4, gl.deliveryOrderType, gl.isBundle, gl.bundlePrice, gl.deliveryPrice2, gl.deliveryPrice3, gl.deliveryPrice4, gl.isRegionOption, gl.regionOptionType, gl.isRegionPrice1, gl.isRegionPrice2, gl.isRegionPrice3, gl.isDelivery, gl.goodsImgInfo, gl.returnInfo, gl.regDate  
									FROM tbl_goodsList AS gl 
									$joinQ 
									$hint 
									WHERE 1=1  
									$groupBy 
									$orderBy 
									$LIMIT";
		$msg 					=	$this->dbm->bindExecute($SQL, $values);

		$SQL					=	"SELECT count(gl.goodsIdx) AS count 
									FROM tbl_goodsList AS gl 
									$hint 
									WHERE 1=1 $where0";
		$msg2 					=	$this->dbm->bindExecute($SQL, $bindValue);
		$temp					=	$msg2->getData();
		if($temp){
			$msg->setMessage($temp[0]['count']);
		} else {
			$msg->setMessage(0);
		}

		return $msg;							
	}

?>