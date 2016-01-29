<?php

class Controller_RoomApi extends Controller_Base {

	protected $uid;

	public function before(){
			$this->isAjax = true;
			parent::before();
			if(empty($_SESSION['uid']))
				$this->echoJson(false,"invalid uid");
			$this->uid = $_SESSION['uid'];
	}

	public function listAction(){

  }

  public function createAction(){
		$name = trim($_POST['name']);
		if(empty($name))
			$this->echoJson(false,"invalid name");
		$password = trim($_POST['password']);
		$roomModel = new RoomModel();
		$res = $roomModel->addRoom($name,$password,$this->uid);
		if($res)
			$this->echoJson(true,$res);
		else
			$this->echoJson(false,"create fail");
  }
}
