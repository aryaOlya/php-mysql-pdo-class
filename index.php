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
        $statement = $this->conn->prepare("insert into"." ".$tableName." "."(".$this->arrayToString($columns).")"." "."values"."(".$this->setBindValues($values).")");
        $statement->execute($values);
    }


    //check the table existence
    public function __invoke($tableName)
    {
        try {
            $statement = $this->conn->prepare("select * from ".$tableName);
            $statement->execute();
            $assoc = $statement->fetch(PDO::FETCH_ASSOC);
            $tableInfo = '';
            foreach ($assoc as $key => $value){
                $tableInfo =$key."=>".$value." | ".$tableInfo."<br />";
            }
            return $tableInfo;
        }catch(PDOException $e){
            return "no such table exist!";
        }
    }


    //convert array to string
    private function arrayToString($columns){
        return implode(',',$columns);
    }

    //convert values to ?
    private function setBindValues($values){
        for($i=0;$i<count($values);$i++){
            $values[$i] = '?';
        }
        return implode(',',$values);
    }

    //execute when dynamic method not found
    public function __call(string $name, array $arguments)
    {
        echo 'dynamic method '.$name.' not found!' ;
    }

    //execute when static method not found
    public static function __callStatic(string $name, array $arguments)
    {
        echo 'static method '.$name.' not found!' ;
    }

    //execute when instance got printed
    public function __toString(): string
    {
        return 'try to connect to the '.$this->dbName.' database!';
    }
}

$myPdo = new UsePdo('localhost','root','','testone');
echo $myPdo('user');
//$myPdo->insertToTable('user',['name'],['ahmad']);


