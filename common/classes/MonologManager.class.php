<?php
namespace common;
class MonologManager{
	private $log;
	private $path;

	/**
	 * 개발 중일시 DEV
	 * 이외의 상황에서는 업체번호 사용
	 */
	public function __construct($channel = 'DEV') {
		global $common;
		use Monolog\Logger;
		use Monolog\Handler\StreamHandler;

		$this->path				=	$common['DOCUMENTROOT'] . '/logs/' . $channel . '/' . date('Y') . '/' . date('Ym') . '/' . date('Ymd') '.log';
		$this->log				=	new Logger($channel);
	}

	public function log_info($msg, $values = array()){
		$this->log->pushHandler(new StreamHandler($this->path, Logger::INFO));
		if($values){
			$this->log->addInfo($msg, $values);
		} else {
			$this->log->addInfo($msg);
		}
		
	}

	public function log_debug($msg, $values = array()){
		$this->log->pushHandler(new StreamHandler($this->path, Logger::DEBUG));
		if($values){
			$this->log->addDebug($msg, $values);
		} else {
			$this->log->addDebug($msg);
		}
	}

	public function log_error($msg, $values = array()){
		$this->log->pushHandler(new StreamHandler($this->path, Logger::ERROR));
		if($values){
			$this->log->addError($msg, $values);
		} else {
			$this->log->addError($msg);
		}
	}
}