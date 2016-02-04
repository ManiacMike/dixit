<?php
class RoomModel extends BaseModel{

  public function getRoomList(){
    $rooms = $this->fetchAll("game","1");
    return $rooms;
  }

  public function getRoomById($id){
    $rooms = $this->fetch("game","id = :id",array("id"=>$id));
    return $rooms;
  }

  public function addRoom($name,$password,$creator){
    $userlist = array(
      $creator=>array(),
    );
    $res = $this->insert("game",array(
      "name"=>$name,
      "password"=>$password,
      "creator"=>$creator,
      "create_time"=>time(),
      "user_count"=>1,
      "user_list"=>json_encode($userlist)
    ));
    return $res;
  }

  public function addUser($rid,$userlist,$uid){
    $userlist[$uid] = array();
    $res = $this->update("game",array(
      "id"=>$rid,
      "user_list"=>json_encode($userlist),
      "user_count"=>BaseModel::INCREMENT_PLACEHOLD,
    ),"id = :id");
    return $res;
  }

  public function removeUser($rid,$userlist,$uid){
    unset($userlist[$uid]);
    $res = $this->update("game",array(
      "id"=>$rid,
      "user_list"=>json_encode($userlist),
      "user_count"=>BaseModel::DECREMENT_PLACEHOLD,
    ),"id = :id");
    return $res;
  }

  public function updateRoom(){
    $res = $this->update("game",array(
      "name"=>"testdddd",
    ),"id=2");
    var_dump($res);
  }

  public function deleteRoom(){
    $res = $this->delete("game","id=2");
    var_dump($res);
  }

  //TODO 改成memcahe
  public function getRoomIdByUid($uid){
    
  }
}
