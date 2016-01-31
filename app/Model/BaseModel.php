<?php
class BaseModel{

  protected $db;

  protected $config;

  const INCREMENT_PLACEHOLD = "#INCREMENT#";
  const DECREMENT_PLACEHOLD = "#DECREMENT#";

  public function __construct(){
    $config = Kiss_Registry::get("config");
    $this->config = $config;
  }

  public function fetchAll($table,$where,$sqlParams = array(),$fetchParams = "*"){
    $sql = "SELECT {$fetchParams} FROM `{$table}` WHERE {$where}";
    $stmt = $this->_prepare($sql,$sqlParams);
    if($stmt->execute() === false){
			new LogDbError(__METHOD__, $stmt);
			return false;
		}
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function fetch($table,$where,$sqlParams = array(),$fetchParams = "*"){
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
    return $this->db->lastInsertId();
  }

  public function update($table,$sqlParams,$where){
    if(empty($sqlParams))
      return false;
    $sql = "UPDATE `$table` set ";
    $sqlParts = array();
    foreach ($sqlParams as $key => $value) {
      if($value == self::INCREMENT_PLACEHOLD){
        $sqlParts[] = "`{$key}` = `{$key}` + 1";
      }elseif ($value == self::DECREMENT_PLACEHOLD) {
        $sqlParts[] = "`{$key}` = `{$key}` - 1";
      }elseif (strpos($where,":".$key) === false){
        $sqlParts[] = "`{$key}` = :{$key}";
      }
    }
    $sql .= implode(",",$sqlParts) . " WHERE {$where}";
    $stmt = $this->_prepare($sql,$sqlParams);
    if($stmt->execute() === false){
      new LogDbError(__METHOD__, $stmt);
      return false;
    }
    return true;
  }

  public function delete($table,$where,$sqlParams = array()){
    $sql = "DElETE FROM `$table` WHERE {$where}";
    $stmt = $this->_prepare($sql,$sqlParams);
    if($stmt->execute() === false){
      new LogDbError(__METHOD__, $stmt);
      return false;
    }
    return true;
  }

  protected function _prepare($sql,$sqlParams){
    $config = $this->config;
    $this->db = new LazyPdoRw($config->db_dixit->dsn,$config->db_dixit->username,$config->db_dixit->password);
    if($this->config->db_debug){
      echo $sql."\n";
    }
    $stmt = $this->db->prepare($sql);
    foreach ($sqlParams as $key => &$value) {
      if(!in_array($value,array(self::INCREMENT_PLACEHOLD,self::DECREMENT_PLACEHOLD))){
        $stmt->bindParam(":".$key, $value);
        if($this->config->db_debug)
          echo "bind :{$key}".$value;
      }
    }
    return $stmt;
  }


}
