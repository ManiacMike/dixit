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
    $this->view->isJoined = isset($userlist[$this->uid]);
    $roomInfo['user_list'] = $userlist;
    $this->view->room = $roomInfo;
    $this->view->pageName = "room";

    $channelServer = new GoChannel();
    $this->render('room/index');
  }

}
