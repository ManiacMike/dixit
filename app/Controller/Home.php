<?php

class Controller_Home extends Controller_Base {

	public function indexAction(){
		$roomModel = new RoomModel();
		$roomList = $roomModel->getRoomList();
		$this->view->roomList = $roomList;
		$this->view->pageName = "index";
		$this->render('home/index');
	}

}
