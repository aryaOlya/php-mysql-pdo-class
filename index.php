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

        //start connection to the $dbName database
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
    private function setBindValues($values){
        for($i=0;$i<count($values);$i++){
            $values[$i] = '?';
        }
        return implode(',',$values);
    }

    //execute when method not found
    public function __call(string $name, array $arguments)
    {
        return 'method '.$name.' not found!' ;
    }
}

$myPdo = new UsePdo('localhost','root','','testone');
$myPdo->insertToTable('user',['name'],['asghar']);


