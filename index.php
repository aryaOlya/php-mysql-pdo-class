<?php

class usePdo{

    public $server;
    public $username;
    public $password;
    public $dbName;
    public $conn;

    public function __construct($server,$username,$password,$dbName=null)
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->dbName = $dbName;
    }

    //start connection to the $dbName database
    public function startConnection(){
        try {
            $this->conn = new PDO("mysql:host=$this->server;dbname=$this->dbName", $this->username, $this->password);
        }catch (PDOException $e){
            echo $e;
        }
    }

    //insert data to the $tableName table columns
    public function insertToTable($tableName,$columns,$values){
        $statement = $this->conn->prepare("insert into"." ".$tableName." "."(".implode(',',$columns).")"." "."values"."(".$this->setBindValues($values).")");
        $statement->execute($values);
    }


    
    //convert values to ?
    public function setBindValues($values){
        for($i=0;$i<count($values);$i++){
            $values[$i] = '?';
        }
        return implode(',',$values);
    }
}

$myPdo = new UsePdo('localhost','root','','testone');
$myPdo->startConnection();
$myPdo->insertToTable('user',['name'],['arya']);


