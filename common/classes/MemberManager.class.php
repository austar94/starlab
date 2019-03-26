<?php

class MarketingManager{
	private $dbm;

	public function __construct() {
		$this->dbm				=	new DBManager();
		$this->fm				=	new FileManager();
	}

	//로그인 기록
	public function insert_loginLog($data){
		global $us;
		$values					=	array();
		$SQL					=	array();

		$userIdx				=	$data['userIdx'];
		$userID					=	$data['userID'];
		$IP						=	$_SERVER['REMOTE_ADDR'];

		//마지막 로그인 정보 업데이트
		$today					=	date('Y-m-d H:i:s');
		$SQL[]					=	"UPDATE tbl_userInfo SET lastLogin = ? WHERE AND userIdx = ?";
		$values[]				=	array($today $userIdx);

		$SQL[]					=	"INSERT INTO tbl_loginIP 
										(userIdx, userID, IP) 
									VALUE 
										(?, ?, ?)";
		$values[]				=	array($userIdx, $userID, $IP);
		$msg 					=	$this->dbm->bindTransaction($SQL, $values, 'Y');
		return $msg;
	}

	//회원 등록
	public function insert_user($data){
		global $us;
		$values					=	array();

		$userID					=	$data['userID'];					//회원 아이디
		$userPWD				=	$data['userPWD'];					//회원 비밀번호
		$userType				=	$data['userType'];					//1:관리자, 2:직원, 3:일반회원
		$loginType				=	$data['loginType'];					//1:일반가입,  2:네이버, 3:카카오, 4:구글, 5:페이스북
		$userName				=	$data['userName'];					//회원 이름
		$userMobile				=	$data['userMobile'];				//회원 핸드폰번호
		$userZip				=	$data['userZip'];					//회원 우편번호
		$userAddr1				=	$data['userAddr1'];					//회원 주소
		$userAddr2				=	$data['userAddr2'];					//회원 주소
		$userMail				=	$data['userMail'];					//회원 이메일주소

		$SQL					=	"INSERT INTO tbl_userInfo 
										(userID, userPWD, userType, loginType, userName, userMobile, userZip, userAddr1, userAddr2, userMail) 
									VALUE 
										(?, PASSWORD(?), ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$values					=	array($userID, $userPWD, $userType, $loginType, $userName, $userMobile, $userZip, $userAddr1, $userAddr2, $userMail);	
		$msg 					=	$this->dbm->bindExecute($SQL, $values, 'Y');
		//멤버 등록에 성공했을경우
		if($msg->isResult()){
			//해당 유저 idx
			$search					=	array(
				'userID'				=>	$userID
			);
			$msg					=	$this->get_userData(1 ,'', '', $search);
			$user					=	$msg->getData();
			$user					=	allStrip($user[0]);
			$userIdx				=	$user['userIdx'];

			$this->cm				=	new CorpManager();
			$mmsg					=	$this->cm->get_corpData();
			$basic 					=	$mmsg->getData();
			$basic					=	allStrip($basic[0]);
			$isOrderPoint			=	$basic['isOrderPoint'];					//포인트 기능 사용여부
			$joinPoint				=	$basic['joinPoint'];					//회원가입 기념 포인트

			//포인트 적립 기능이 활성화 되어있을 경우
			if($isOrderPoint){

				//회원가입 지급 포인트가 0이상일 경우
				if($joinPoint > 0){
					$this->pm			=	new PointManager();
					$data				=	array(
						'userIdx'		=>	$userIdx,
						'pointContent'	=>	$userName . '님의 회원가입을 환영합니다! 회원가입 ' . number_format($joinPoint) . '적립금 지급',
						'savingPoint'	=>	$joinPoint,
						'relevantTable'	=>	'@join',
						'relevantID'	=>	$userIdx,
						'relevantAction'=>	'join' . '-' . uniqid('')
					);
					$this->pm->insertPoint($data);
				}
			}
		}

		return 	$msg;
	}

	//회원리스트
	public function get_userData($pageNo = '', $recordPerPage = '', $userIdx = '', $data = array()){
		global $us;
		$values					=	array();
		$where0					=	'';
		$LIMIT					=	'';

		$userCode				=	$data['userCode'];					//회원 코드
		$userMobile				=	$data['userMobile'];				//회원 핸드폰
		$userID					=	$data['userID'];					//회원 아이디
		$userPWD				=	$data['userPWD'];					//회원 비밀번호
		$userState				=	$data['userState'];					//회원 상태
		$userType				=	$data['userType'];					//1:관리자, 2:직원, 3:일반회원
		$loginType				=	$data['loginType'];					//1:일반, 2:네이버, 3:카카오, 4:구글, 5:페이스북
		$searchType				=	$data['searchType'];
		$searchWord				=	$data['searchWord'];
		$join					=	$data['join'];						//테이블 조인
		$amount					=	$data['amount'];					//구매금액
		$minAmount				=	$data['minAmount'];					//최소 구매금액
		$maxAmount				=	$data['maxAmount'];					//최대 구매금액
		$dateS					=	$data['dateS'];						//등록일
		$dateE					=	$data['dateE'];						//등록일
		$userName				=	$data['userName'];
		$userMail				=	$data['userMail'];


		$order					=	$data['order'];						//정렬

		$select0				=	'';
		$values[]				=	$us['corpIdx'];
		if($amount){
			$select0			.=	", (
				SELECT SUM( oi.amt ) 
				FROM tbl_orderInfo AS oi
				WHERE oi.corpIdx =  '$us[corpIdx]'
				AND oi.userIdx = ui.userIdx
				AND oi.orderState
				) AS amount";

			if($minAmount){
				$where0			.=	" AND ui.minAmount <= ?";
				$values[]		=	$minAmount;
			}
			if($minAmount){
				$where0			.=	" AND ui.maxAmount >= ?";
				$values[]		=	$maxAmount;
			}
		}
		
		if($userCode){
			$where0				.=	" AND ui.userCode = ?";
			$values[]			=	$userCode;
		}
		if($userID){
			$where0				.=	" AND ui.userID = ?";
			$values[]			=	$userID;
		}

		if($userPWD){
			$where0				.=	" AND ui.userPWD = PASSWORD(?)";
			$values[]			=	$userPWD;
		}
		if($userType){
			if(is_array($userType)){
				$where0			.=	" AND ui.userType IN (?)";
				$value			=	'';
				for($i = 0; $i < sizeof($userType); $i++){
					$dot		=	($i == 0) ? '' : ',';
					$value		.=	$dot . $userType[$i];
				}
				$values[]		=	$value;
			} else {
				$where0			.=	" AND ui.userType = ?";
				$values[]		=	$userType;
			}

		}
		if($dateS){
			$where0				.=	" AND ui.regDate >= ?";
			$values[]			=	$dateS . ' 00:00';
		}
		if($dateE){
			$where0				.=	" AND ui.regDate <= ?";
			$values[]			=	$dateE . ' 23:59';
		}
		if($userMobile){
			$where0				.=	" AND ui.userMobile = ?";
			$values[]			=	$userMobile;
		}
		if($userName){
			$where0				.=	" AND ui.userName = ?";
			$values[]			=	$userName;
		}
		if($userMail){
			$where0				.=	" AND ui.userMail = ?";
			$values[]			=	$userMail;
		}
		if($userIdx){
			$where0				.=	" AND ui.userIdx = ?";
			$values[]			=	$userIdx;
		}

		if($searchWord){
			if($searchType == 1){
				$where0			.=	" AND ui.userID LIKE ?";
				$values[]		=	'%'.$searchWord.'%';
			} else if($searchType == 2){
				$where0			.=	" AND ui.userName LIKE ?";
				$values[]		=	'%'.$searchWord.'%';
			} else if($searchType == 3){
				$where0			.=	" AND ui.userMobile LIKE ?";
				$values[]		=	'%'.$searchWord.'%';
			}
		}
		
		if($loginType){
			if(is_array($loginType)){
				$where0			.=	" AND ui.loginType IN (?)";
				$value			=	'';
				for($i = 0; $i < sizeof($loginType); $i++){
					$dot		=	($i == 0) ? '' : ',';
					$value		.=	$dot . $loginType[$i];
				}
				$values[]		=	$value;
			} else {
				$where0			.=	" AND ui.loginType = ?";
				$values[]		=	$loginType;
			}
		}
		
		if($userState){
			if(is_array($userState)){
				$where0			.=	" AND ui.userState IN (?)";
				$value			=	'';
				for($i = 0; $i < sizeof($userState); $i++){
					$dot		=	($i == 0) ? '' : ',';
					$value		.=	$dot . $userState[$i];
				}
				$values[]		=	$value;
			} else {
				$where0			.=	" AND ui.userState = ?";
				$values[]		=	$userState;
			}
		}
		
		$orderBy				=	$order			?	"ORDER BY $order"		:	'';
		
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

		$SQL					=	"SELECT ui.corpIdx, ui.userIdx, ui.userID, ui.userName, ui.userMobile, ui.userMail, ui.userCode, ui.loginType, ui.userType, ui.userState, ui.userZip, ui.userAddr1, ui.userAddr2, ui.userLevel, ui.userPoint, ui.lastLogin, ui.manageMsg, ui.isSMS, ui.isMail, ui.regDate $select0  
									FROM tbl_userInfo AS ui  
									WHERE ui.corpIdx = ? $where0 
									$orderBy 
									$LIMIT";
		$msg 					=	$this->dbm->bindExecute($SQL, $values);

		$SQL					=	"SELECT count(ui.userIdx) AS count 
									FROM tbl_userInfo AS ui 
									WHERE ui.corpIdx = ? $where0";
		$msg2 					=	$this->dbm->bindExecute($SQL, $bindValue);
		$temp					=	$msg2->getData();
		if($temp){
			$msg->setMessage($temp[0]['count']);
		} else {
			$msg->setMessage(0);
		}

		return 	$msg;
	}

?>