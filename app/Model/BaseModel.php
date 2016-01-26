<?php
class BaseModel{

  protected $db;

  protected $config;

  public function __construct($withDb = false){
    $config = Kiss_Registry::get("config");
    $this->config = $config;
    if($withDb)
      $this->db = new LazyPdoRw($config->db_dixit->dsn,$config->db_dixit->username,$config->db_dixit->password);
  }

  public function fetch($table,$where,$fetchParams = "*",$sqlParams = array()){
    $sql = "SELECT {$fetchParams} FROM `{$table}` WHERE {$where}";
    $stmt = $this->_prepare($sql,$sqlParams);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function fetchSingle($table,$where,$fetchParams = "*",$sqlParams = array()){
    $sql = "SELECT {$fetchParams} FROM `{$table}` WHERE {$where}";
    $stmt = $this->_prepare($sql,$sqlParams);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function insert($table,$data){
    $sql = "INSERT INTO `{table}`";
  }

  public function update($table,$data,$where,$sqlParams = array()){

  }

  public function delete($table,$where){

  }

  protected function _prepare($sql,$sqlParams){
    $stmt = $this->db->prepare($sql);
    foreach ($sqlParams as $key => $value) {
      $stmt = $stmt->bindParam($key, $value);
    }
    return $stmt;
  }


}
