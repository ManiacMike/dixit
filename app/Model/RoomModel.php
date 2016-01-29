<?php
class RoomModel extends BaseModel{

  public function getRoomList(){
    $rooms = $this->fetchAll("game","1");
    return $rooms;
  }

  public function addRoom($name,$password,$creator){
    $res = $this->insert("game",array(
      "name"=>$name,
      "password"=>$password,
      "creator"=>$creator,
      "create_time"=>time()
    ));
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
}
