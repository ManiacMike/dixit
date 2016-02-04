<?php
class Controller_GoChannelApi  extends Controller_BaseApi{

    //callback from go channel service
    public function getAction(){

    }

    public function loseAction(){

    }

    public function msgAction(){
      if($_POST['event'] == "message"){
        $data = json_decode($_POST['data'],true);
        if($data['room_id']){
          $roomModel = new RoomModel();
          $room = $roomModel->getRoomById($data['room_id']);
          $userlist = json_decode($room['user_list'],true);
          $userlist = $userlist?$userlist:array();
          $uids = array_keys($userlist);
          $channelServer =  new GoChannel();
          $channelServer->push(implode(",", $uids),$_POST['data']);
        }
      }
    }
}
