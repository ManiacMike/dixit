<?php
class BaseModel{

  protected $db;

  protected $config;

  public function __construct(){
    $config = Kiss_Registry::get("config");
    $this->config = $config;
  }

  public function fetchAll($table,$where,$fetchParams = "*",$sqlParams = array()){
    $sql = "SELECT {$fetchParams} FROM `{$table}` WHERE {$where}";
    $stmt = $this->_prepare($sql,$sqlParams);
    if($stmt->execute() === false){
			new LogDbError(__METHOD__, $stmt);
			return false;
		}
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function fetch($table,$where,$fetchParams = "*",$sqlParams = array()){
    $sql = "SELECT {$fetchParams} FROM `{$table}` WHERE {$where}";
    $stmt = $this->_prepare($sql,$sqlParams);
    if($stmt->execute() === false){
      new LogDbError(__METHOD__, $stmt);
      return false;
    }
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  public function insert($table,$sqlParams){
    if(empty($sqlParams))
      return false;
    $sql = "INSERT INTO `{$table}`";
    $keys = array_keys($sqlParams);
    $sql .= "(".implode(",", $keys).") VALUES";
    $values = array_values($sqlParams);
    $sql .= " (:". implode(",:",$keys) .")";
    $stmt = $this->_prepare($sql,$sqlParams);
    if($stmt->execute() === false){
      new LogDbError(__METHOD__, $stmt);
      return false;
    }
    return true;
  }

  public function update($table,$sqlParams,$where){
    if(empty($sqlParams))
      return false;
    $sql = "UPDATE `$table` set ";
    foreach ($sqlParams as $key => $value) {
        $sql .= "`{$key}` = :$key";
    }
    $sql .= " WHERE {$where}";
    $stmt = $this->_prepare($sql,$sqlParams);
    if($stmt->execute() === false){
      new LogDbError(__METHOD__, $stmt);
      return false;
    }
    return true;
  }

  public function delete($table,$where){
    //todo injection danger on where
    $sql = "DElETE FROM `$table` WHERE {$where}";
    $stmt = $this->_prepare($sql,array());
    if($stmt->execute() === false){
      new LogDbError(__METHOD__, $stmt);
      return false;
    }
    return true;
  }

  protected function _prepare($sql,$sqlParams){
    $config = $this->config;
    $this->db = new LazyPdoRw($config->db_dixit->dsn,$config->db_dixit->username,$config->db_dixit->password);
    $stmt = $this->db->prepare($sql);
    foreach ($sqlParams as $key => &$value) {
      $stmt->bindParam(":".$key, $value);
    }
    return $stmt;
  }


}
