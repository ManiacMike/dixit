<?php
class RoomModel extends BaseModel{

  public function getRoomList(){
    $rooms = $this->fetchAll("game","1");
    return $rooms;
  }

  public function addRoom(){
    $res = $this->insert("game",array(
      "name"=>"test",
      "creator"=>123,
      "create_time"=>time()
    ));
    var_dump($res);
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
