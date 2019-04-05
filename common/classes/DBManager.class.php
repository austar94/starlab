<?php
include_once $common['commonPath'] . '/inc/dbInfo.php';
include_once $common['commonPath'] . '/classes/Message.class.php';
/**
 * @date		2019-02-28
 * @author		star
 * @details		바인드쿼리 추가
 * @details		로그파일 - Ym 로 생성되도록 수정
 *	DB오류났을경우 해당 유저의 외부 DB접속가능여부확인 SQL실행문 권한 여부 확인
 */
class DBManager {
	private $conn;

	public function __construct() {
		global $dbHost, $dbName, $dbID, $dbPWD, $dbPort;

		$this->conn				=	mysqli_connect($dbHost, $dbID, $dbPWD, $dbName, $dbPort);

		if (!$this->conn) {
			echo '<br>DB 작업중이거나 연결 되지 않았습니다.<br>';
			exit;
		}

		$result					=	@mysqli_select_db($this->conn, $dbName);

		if (!$result) {
			echo '<br>DB 작업중이거나 연결 되지 않았습니다.<br>';
			exit;
		}
		@mysqli_query($this->conn, 'set names utf8');
	}
	
	public function __destruct() {
		$this->dbClose();
	}
	
    
    //바인드 사용시 값정리
	function fetchAssocStatement($stmt)
    {
        if($stmt->num_rows>0)
        {
            $result = array();
            $md = $stmt->result_metadata();
            $params = array();
            while($field = $md->fetch_field()) {
                $params[] = &$result[$field->name];
            }
            call_user_func_array(array($stmt, 'bind_result'), $params);
            if($stmt->fetch())
                return $result;
        }

        return null;
    }

	/**
	 * @date			2019-02-13
	 * @author			star
	 * @details			쿼리바인드를 사용하기 위한 실행구문
	 */
	public function bindExecute($query, $values = '', $logSave = ''){
		global $common;
		global $us;

		$msg					=	new Message();
		$data					=	array();
		$k						=	0;
		//준비
		$stmt					=	$this->conn->prepare($query);

        //DB오류 발생시
		if ( !$stmt ) {
			$msg->setResult(false);
			//$msg->setMessage(mysql_error());
			$msg->setMessage(mysqli_error($this->conn));
		} else {

			//변수값이 존재할경우
			if($values){
				// $inputArray[]		=	&$types;
				$inputArray			=	array();
				$type				=	'';
				for($i = 0; $i < sizeof($values); $i++){
					$inputArray[]	=	&$values[$i];
				 	$type			.=	(gettype($values[$i]) == 'integer')		?	'i' : 's';
				}
				array_unshift($inputArray, $type);
				call_user_func_array(array($stmt, 'bind_param'), $inputArray);
			}

			//실행
			if ( !$stmt->execute() ) {
				$msg->setResult(false);
				//$msg->setMessage(mysql_error());
				$msg->setMessage(mysqli_error($this->conn));
			}

			$stmt->store_result();
			$size				=	$stmt->num_rows;

			if ($logSave == 'I') {
				$lastID			=	$this->conn->insert_id;
				$msg->setData($lastID);
			}

			if ($size != 0) {
				while($assoc_array = $this->fetchAssocStatement($stmt))
    			{
					$data[$k++] = $assoc_array;
				}
				$msg->setData($data);
			}

			$msg->setResult(true);
		}

		if ( $logSave == 'Y' || $logSave == 'I' ) {

            //로그 폴더 생성
			$uploadPath			=	$common['FileManager']->setMakeDir($_SERVER['DOCUMENT_ROOT'] . '/saveLog', '', '', date('Ym'), $common['dirPermission']);
			$ipAddr				=	$_SERVER['REMOTE_ADDR'];
			$logfile			=	fopen($uploadPath . '/DBSave_' . date('Y-m-d',time()) . '.log', 'a+');
			fwrite( $logfile, "\r\n\r\n");
			fwrite( $logfile, "================================================\r\n");
			fwrite( $logfile, date('Y-m-d H:i:s',time()) . "\r\n" . $us['userIdx'] . " | " . $ipAddr . " | " . $_SERVER['PHP_SELF'] . "\r\n");
			fwrite( $logfile, "\r\n" . $query . "\r\n");
			fclose( $logfile );
		} else if ( $logSave == 'D' ) {
			$ipAddr				=	$_SERVER['REMOTE_ADDR'];
			$logfile			=	fopen($_SERVER['DOCUMENT_ROOT'] . '/saveLog/debug/DBSave_' . date('Y-m-d',time()) . '.log', 'a+');
			fwrite( $logfile, "\r\n\r\n");
			fwrite( $logfile, "======================================================\r\n");
			fwrite( $logfile, date('Y-m-d H:i:s',time()) . "\r\n" . $us['userIdx'] . " | " . $ipAddr . " | " . $_SERVER['PHP_SELF'] . "\r\n");
			fwrite( $logfile, "\r\n" . $query . "\r\n");
			fclose( $logfile );
		}

		return $msg;
	}

	/**
	 * @date			2019-02-13
	 * @author			star
	 * @details			쿼리바인드를 사용하기 위한 실행구문
	 * 					다수의 쿼리를 배열로 받아 트랜잭션 처리합니다.
	 */
	public function bindTransaction($queries, $values = array(), $logSave = ''){
		global $common;
		global $us;

		$msg					=	new Message();

		mysqli_query($this->conn, 'Start Transaction');

		foreach ($queries as $query_no=>$query) {
			if ($logSave == 'Y') {
				//로그 폴더 생성
			    $uploadPath			=	$common['FileManager']->setMakeDir($_SERVER['DOCUMENT_ROOT'] . '/saveLog', '', '', date('Ym'), $common['dirPermission']);
				$ipAddr				=	$_SERVER['REMOTE_ADDR'];
				$logfile			=	fopen($uploadPath . '/DBSave_' . date('Y-m-d',time()) . '.log', 'a+');
				fwrite( $logfile, "\r\n\r\n");
				fwrite( $logfile, "=================================================\r\n");
				fwrite( $logfile, date('Y-m-d H:i:s',time()) . "\r\n" . $us['userIdx'] . " | " . $ipAddr . " | " . $_SERVER['PHP_SELF'] . "\r\n");
				fwrite( $logfile, "\r\n" . $query . "\r\n");
				fclose( $logfile );
			} else if ( $logSave == 'D' ) {
				$ipAddr				=	$_SERVER['REMOTE_ADDR'];
				$logfile			=	fopen($_SERVER['DOCUMENT_ROOT'] . '/saveLog/debug/DBSave_' . date('Y-m-d',time()) . '.log', 'a+');
				fwrite( $logfile, "\r\n\r\n");
				fwrite( $logfile, "=======================".$us['userIdx']."===========================\r\n");
				fwrite( $logfile, date('Y-m-d H:i:s',time()) . "\r\n" . $us['userID'] . " | " . $ipAddr . " | " . $_SERVER['PHP_SELF'] . "\r\n");
				fwrite( $logfile, "\r\n" . $query . "\r\n");
				fclose( $logfile );
			}

			$stmt					=	$this->conn->prepare($query);

			if ( !$stmt ) {
				$this->conn->rollback();
				$msg->setMessage(($query_no + 1) . '번 쿼리에서 오류가 발생했습니다.' . $query);
				return $msg;
			}

			//변수값이 존재할경우
			if($values[$query_no]){
				$inputArray			=	array();
				$type				=	'';
				//$inputArray[]		=	&$types[$query_no];
				for($i = 0; $i < sizeof($values[$query_no]); $i++){
				  $inputArray[]		=	&$values[$query_no][$i];
				  $type				.=	(gettype($values[$query_no][$i]) == 'integer')		?	'i' : 's';
				}
				
				array_unshift($inputArray, $type);
				call_user_func_array(array($stmt, 'bind_param'), $inputArray);
			}
			
			if ( !$stmt->execute() ) {
				$this->conn->rollback();
				$msg->setMessage(($query_no + 1) . '번 쿼리에서 오류가 발생했습니다.' . $query);
				return $msg;
			}
		}

		// close prepared statement
		$stmt->close();

		// commit transaction
		$this->conn->commit();
		$msg->setResult(true);
		return $msg;
	}

	//	I면 로그 기록 후 insert_id 가져오기, Y면 로그 기록만 하기, N 이면 로그 기록 안하기
	public function execute($query, $logSave = '') {
		global $common;
		global $us;

		$msg					=	new Message();
		$data					=	array();
		$i						=	0;
		$result					=	mysqli_query($this->conn, $query);

		if ( !$result ) {
			$msg->setResult(false);
			//$msg->setMessage(mysql_error());
			$msg->setMessage(mysqli_error($this->conn));
		} else {
			$size				=	$result->num_rows;

			if ($logSave == 'I') {
				$lastID			=	$this->conn->insert_id;
				$msg->setData($lastID);
			}

			if ($size != 0) {
				while ($row = @mysqli_fetch_array($result)) {
					$data[$i++] = $row;
				}
				$msg->setData($data);
			}

			$msg->setResult(true);
		}

		if ( $logSave == 'Y' || $logSave == 'I' ) {
			//로그 폴더 생성
			$uploadPath			=	$common['FileManager']->setMakeDir($_SERVER['DOCUMENT_ROOT'] . '/saveLog', '', '', date('Ym'), $common['dirPermission']);
			$ipAddr				=	$_SERVER['REMOTE_ADDR'];
			$logfile			=	fopen($uploadPath . '/DBSave_' . date('Y-m-d',time()) . '.log', 'a+');
			fwrite( $logfile, "\r\n\r\n");
			fwrite( $logfile, "=============================".$us['userIdx']."==========================\r\n");
			fwrite( $logfile, date('Y-m-d H:i:s',time()) . "\r\n" . $us['userID'] . " | " . $ipAddr . " | " . $_SERVER['PHP_SELF'] . "\r\n");
			fwrite( $logfile, "\r\n" . $query . "\r\n");
			fclose( $logfile );
		} else if ( $logSave == 'D' ) {
			$ipAddr				=	$_SERVER['REMOTE_ADDR'];
			$logfile			=	fopen($_SERVER['DOCUMENT_ROOT'] . '/saveLog/debug/DBSave_' . date('Y-m-d',time()) . '.log', 'a+');
			fwrite( $logfile, "\r\n\r\n");
			fwrite( $logfile, "=======================".$us['userIdx']."=================================\r\n");
			fwrite( $logfile, date('Y-m-d H:i:s',time()) . "\r\n" . $us['userID'] . " | " . $ipAddr . " | " . $_SERVER['PHP_SELF'] . "\r\n");
			fwrite( $logfile, "\r\n" . $query . "\r\n");
			fclose( $logfile );
		}

		return $msg;
	}


	/**
	 * 다수의 쿼리를 배열로 받아 트랜잭션 처리합니다.
	 **/
	public function transaction($queries, $logSave = '') {
		global $common;
		global $us;

		$msg					=	new Message();

		mysqli_query($this->conn, 'Start Transaction');

		foreach ($queries as $query_no=>$query) {
			if ($logSave == 'Y') {
				//로그 폴더 생성
			    $uploadPath			=	$common['FileManager']->setMakeDir($_SERVER['DOCUMENT_ROOT'] . '/saveLog', '', '', date('Ym'), $common['dirPermission']);
				$ipAddr				=	$_SERVER['REMOTE_ADDR'];
				$logfile			=	fopen($uploadPath . '/DBSave_' . date('Y-m-d',time()) . '.log', 'a+');
				fwrite( $logfile, "\r\n\r\n");
				fwrite( $logfile, "====================corpIdx : ".$us['userIdx']."============================\r\n");
				fwrite( $logfile, date('Y-m-d H:i:s',time()) . "\r\n" . $us['userID'] . " | " . $ipAddr . " | " . $_SERVER['PHP_SELF'] . "\r\n");
				fwrite( $logfile, "\r\n" . $query . "\r\n");
				fclose( $logfile );
			} else if ( $logSave == 'D' ) {
				$ipAddr				=	$_SERVER['REMOTE_ADDR'];
				$logfile			=	fopen($_SERVER['DOCUMENT_ROOT'] . '/saveLog/debug/DBSave_' . date('Y-m-d',time()) . '.log', 'a+');
				fwrite( $logfile, "\r\n\r\n");
				fwrite( $logfile, "=====================corpIdx : ".$us['userIdx']."==============================\r\n");
				fwrite( $logfile, date('Y-m-d H:i:s',time()) . "\r\n" . $us['userID'] . " | " . $ipAddr . " | " . $_SERVER['PHP_SELF'] . "\r\n");
				fwrite( $logfile, "\r\n" . $query . "\r\n");
				fclose( $logfile );
			}

			if (!mysqli_query($this->conn, $query)) {
				mysqli_query($this->conn, 'ROLLBACK');
				$msg->setMessage(($query_no + 1) . '번 쿼리에서 오류가 발생했습니다.' . $query);
				return $msg;
			}
		}

		mysqli_query($this->conn, 'COMMIT');
		$msg->setResult(true);
		return $msg;
	}

	//	data Reset
	public function dataReset($result) {
		mysqli_data_seek($result, 0);
	}

	//	db close
	public function dbClose() {
		mysqli_close($this->conn);
	}
}
?>
