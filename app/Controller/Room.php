<?php

class Controller_Room extends Controller_Base {

  public function indexAction(){
    $roomId = trim($_GET['room_id']);
    if(!$roomId)
      $this->errorPage();
    $roomModel = new RoomModel();
    $roomInfo = $roomModel->getRoomById($roomId);
    if(!$roomInfo)
      $this->errorPage();
    $userlist = json_decode($roomInfo['user_list'],true);
    $userlist = $userlist?$userlist:array();
    $this->view->isJoined = in_array($this->uid, $userlist);
    $roomInfo['user_list'] = $userlist;
    $this->view->room = $roomInfo;
    $this->view->pageName = "room";
    $config = Kiss_Registry::get("config");
    $this->view->wsHost = $config->gochannel_url;
    $this->render('room/index');
  }
}
