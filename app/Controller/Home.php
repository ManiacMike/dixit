<?php

class Controller_Home extends Controller_Base {

	public function indexAction(){
		if(empty($_SESSION['uid'])){
			$_SESSION['uid'] = uniqid();
		}
		$roomModel = new RoomModel();
		$roomList = $roomModel->getRoomList();
		$this->view->roomList = $roomList;

		$this->view->title = '只言片语dixit';
		$this->view->pageName = "index";
		$this->render('home/index');
	}

	public function hiAction(){
		$name = $_GET['username'];
		if(!$name) {
			$name = 'World';
		}

		echo 'Hello '.ucfirst($name);
	}

}
