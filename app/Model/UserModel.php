<?php
class UserModel extends BaseModel{

  public function getRoomList(){
    $res = $this->fetch("game","1");
    var_dump($res);
  }
}
