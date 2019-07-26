<?php
namespace star\common\classes;
class CorpManager
{
	private $dbm;

	public function __construct()
	{
		$this->dbm 				= 	new DBManager();
	}


	/**
	 * @date			2019-01-18
	 * @author			star
	 * @details			업체 정보
	 */
	public function get_corpData($pageNo = '', $recordPerPage = '', $corpIdx = '', $data = array()){
		global $us;
		$values					=	array();
		$where0					=	'';

		$corpIdx				=	$corpIdx		?	$corpIdx	:	$us['corpIdx'];
		$corpSub				=	$data['corpSub'];				//서브도메인 명
		

		if($corpIdx){
			$where0				.=	" AND corpIdx = ?";
			$values[]			=	$corpIdx;
		}
		if($corpSub){
			$where0				.=	" AND corpSub = ?";
			$values[]			=	$corpSub;
		}
	

		$SQL					=	"SELECT corpIdx, siteName, ceoName, corpPhone, corpMail, corpAddr, corpNum, mailOrderNum, workTime, corpName, corpSub, mainMetaTitle, mainMeteDescription, mainMetaKeyword, goodsMetaTitle, goodsMetaDescription, goodsMetaKeyword, goodsMetaHashTag, basicDelivery, guamPrice, deliveryType, deliveryTypeQty3, deliveryTypeQty4, deliveryOrderType, isBundle, bundlePrice, deliveryPrice2, deliveryPrice3, deliveryPrice4, isRegionOption, regionOptionType, isRegionPrice1, isRegionPrice2, isRegionPrice3, isCard, isMobile, isTransfer, isBank, serviceTerm, privacyTerm, isKakao, kakaoREST, kakaoJavascript, isNaver, naverClientID, naverClientSecret, isOrderPoint, pointPercent, pointGive, minUsePoint, minOrderPoint, maxUsePoint, joinPoint, pointTerm, isCoupon, reCoupon, returnForm, smsUrl, smsID, smsKey, smsNumber, regDate 
									FROM tbl_corpInfo  
									WHERE 1=1 $where0";
		$msg 					=	$this->dbm->bindExecute($SQL, $values);
		return $msg;
	}
?>