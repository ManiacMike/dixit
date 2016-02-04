<?php

class Controller_RoomApi extends Controller_BaseApi {

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

	public function joinAction(){
		//TODO one user only can join one
		$rid = trim($_POST['rid']);
		$roomModel = new RoomModel();
		$room = $roomModel->getRoomById($rid);
		if($room['password'] != "" && (empty($_POST['password']) || trim($_POST['password'] != $room['password']))){
			$this->echoJson(false,"wrong password");
		}
		$userlist = json_decode($room['user_list'],true);
		$userlist = $userlist?$userlist:array();
		if(isset($userlist[$this->uid])){
			$this->echoJson(true,$rid);
		}else{
			$res = $roomModel->addUser($rid,$userlist,$this->uid);
			if($res)
				$this->echoJson(true,$rid);
			else
				$this->echoJson(false,"join fail");
		}
	}

	public function quitAction(){
		$rid = trim($_POST['rid']);
		$roomModel = new RoomModel();
		$room = $roomModel->getRoomById($rid);
		$userlist = json_decode($room['user_list'],true);
		$userlist = $userlist?$userlist:array();
		if(!isset($userlist[$this->uid])){
			$this->echoJson(true,$rid);
		}else{
			$res = $roomModel->removeUser($rid,$userlist,$this->uid);
			if($res)
				$this->echoJson(true,$rid);
			else
				$this->echoJson(false,"db fail");
		}
	}
}
