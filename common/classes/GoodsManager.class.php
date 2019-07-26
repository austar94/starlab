<?php
namespace star\common\classes;
class GoodsManager
{
	private $dbm;

	public function __construct()
	{
		$this->dbm 				= 	new DBManager();
	}

	/**
	 * @date			2019-02-08
	 * @author			star
	 * @details			카테고리 리스트
	 */
	public function get_cateList($pageNo = '', $recordPerPage = '', $cateCode = '', $data = array()){
		global $us;
		$set0					=	'';
		$joinQ					=	'';
		$where0					=	'';
		$LIMIT					=	'';
		$values					=	array();
		
		$cateLevel				=	$data['cateLevel'];
		$count					=	$data['count'];
		$isUse					=	$data['isUse'];
		$isCheck				=	$data['isCheck'];
		$order					=	$data['order'];
		$cateSeqU				=	$data['cateSeqU'];
		$cateSeqD				=	$data['cateSeqD'];
		$cateSeq				=	$data['cateSeq'];
		$cateName				=	$data['cateName'];
		$cateIdx				=	$data['cateIdx'];
		$join					=	$data['join'];
		$group					=	$data['group'];

		if($isUse){
			$where0				.=	" AND cl.cateLevel = ?";
			$values[]			=	$cateLevel;
		}

		if($isUse){
			$where0				.=	" AND cl.isUse = ?";
			$values[]			=	$isUse;
		}
		
		if($isCheck){
			$where0				.=	$isCheck 		?	" AND cl.isCheck = ?"		:	'';
			$values[]			=	$isCheck;
		}
		
		if($cateCode){
			$where0				.=	$cateCode 		?	" AND cl.cateCode = ?"		:	'';
			$values[]			=	$cateCode;
		}

		if($cateSeq){
			$where0				.=	$cateSeq 		?	" AND cl.cateSeq = ?"		:	'';
			$values[]			=	$cateSeq;
		}

		if($cateSeqU){
			$where0				.=	$cateSeqU 		?	" AND cl.cateSeq > ?"		:	'';
			$values[]			=	$cateSeqU;
		}

		if($cateSeqD){
			$where0				.=	$cateSeqD 		?	" AND cl.cateSeq < ?"		:	'';
			$values[]			=	$cateSeqD;
		}

		if($cateName){
			$where0				.=	$cateName 		?	" AND cl.cateName = ?"		:	'';
			$values[]			=	$cateName;
		}

		if($cateIdx){
			$where0				.=	$cateIdx		?	" AND cl.cateIdx = ?"		:	'';
			$values[]			=	$cateIdx;
		}

	
		$groupBy				=	$group			?	" GROUP BY $group"			:	'';
		$orderBy				=	$order			?	" ORDER BY $order"			:	'';
		

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

		$SQL					=	"SELECT cl.cateIdx, cl.cateCode, cl.cateSeq, cl.cateLevel, cl.cateName, cl.isCheck, cl.regDate $set0 
									FROM tbl_categoryList AS cl 
									WHERE cl.corpIdx = ? $where0 
									$groupBy 
									$orderBy 
									$LIMIT";
		$msg 					=	$this->dbm->bindExecute($SQL, $values, '');
		return $msg;
	}

	/**
	 * @date		2019-02-12
	 * @author		star
	 * @details		상품 옵션
	 */
	public function get_godosOpList($pageNo = '', $recordPerPage = '', $opCode = '', $data = array()){
		global $us;
		$values					=	array();
		$where0					=	'';

		$opName1				=	$data['opName1'];
		$opName2				=	$data['opName2'];
		$opName3				=	$data['opName3'];
		$selOpLevel				=	$data['selOpLevel'];
		$goodsCode				=	$data['goodsCode'];
		$opType					=	$data['opType'];
		$isUse					=	$data['isUse'];
		$isSale					=	$data['isSale'];

		$order					=	$data['order'];
		$group					=	$data['group'];

		if($opCode){
			$where0				.=	" AND gp.opCode = ?";
			$values[]			=	$opCode;
		}

		if($selOpLevel){
			if($selOpLevel == 1){
				if($opName1){
					$where0				.=	" AND gp.opName1 = ?";
					$values[]			=	$opName1;
				}
			} else if($selOpLevel){
				if($opName1){
					$where0				.=	" AND gp.opName1 = ?";
					$values[]			=	$opName1;
				}
				if($opName2){
					$where0				.=	" AND gp.opName2 = ?";
					$values[]			=	$opName2;
				}
			}
		} else {
			if($opName1){
				$where0				.=	" AND gp.opName1 = ?";
				$values[]			=	$opName1;
			}
			if($opName2){
				$where0				.=	" AND gp.opName2 = ?";
				$values[]			=	$opName2;
			}
		}
		

		if($goodsCode){
			$where0				.=	" AND gp.goodsCode = ?";
			$values[]			=	$goodsCode;
		}
		if($opType){
			$where0				.=	" AND gp.opType = ?";
			$values[]			=	$opType;
		}
		if($isUse){
			$where0				.=	" AND gp.isUse = ?";
			$values[]			=	$isUse;
		}
		if($isSale){
			$where0				.=	" AND gp.isSale = ?";
			$values[]			=	$isSale;
		}
		
		$orderBy				=	$order			?	" ORDER BY $order"						:	'';
		$groupBy				=	$group			?	" GROUP BY $group"						:	'';

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


		$SQL					=	"SELECT gp.opIdx, gp.opCode, gp.opType, gp.goodsCode, gp.opStock, gp.opHead1, gp.opHead2, gp.opHead3, gp.opLevel, gp.opName1, gp.opName2, gp.opName3, gp.opPrice, gp.isSale, gp.isUse    
									FROM tbl_goodsOpList AS gp 
									WHERE 1=1 $where0 
									$orderBy 
									$groupBy 
									$LIMIT";
		$msg 					=	$this->dbm->bindExecute($SQL, $values);
		return $msg;
	}

	//상품리스트
	public function get_goodsList($pageNo = '', $recordPerPage = '', $goodsCode = '', $data = array()){
		global $us;
		$values					=	array();
		$joinQ					=	'';
		$where0					=	'';
		$LIMIT					=	'';

		$category1				=	$data['category1'];							//대 카테고리 idx
		$category2				=	$data['category2'];							//중 카테고리 idx
		$category3				=	$data['category3'];							//소 카테고리 idx
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