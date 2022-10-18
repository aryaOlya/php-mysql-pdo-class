<?php

class usePdo{
    protected $server;
    protected $username;
    protected $password;
    protected $dbName;
    protected $conn;

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
        return $this;
    }

    //update columns of table
    public function updateTable($tableName,$columns,$values,$uniqueKey,$uniqueValue){
        $statement = $this->conn->prepare("update ".$tableName." set ".$this->bindParamValueForUpdate($columns)." where ".$uniqueKey."=?");
        $values[] = $uniqueValue;
        $statement->execute($values);
        return $this;
    }

    //check the table existence
    public function __invoke($tableName)
    {
        try {
            $statement = $this->conn->prepare("select * from ".$tableName);
            $statement->execute();
            return "table"." ".$tableName." found";
        }catch(PDOException $e){
            return "no such table exist!";
        }
    }

    //make bind params & value for updating column
    private function bindParamValueForUpdate($columns){
        $bindParamValue ="";
        for($i=0;$i<count($columns);$i++){
            $bindParamValue = $columns[$i]."="."?".$bindParamValue;
        }
        return $bindParamValue;
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
        echo 'dynamic method '."<b>".$name."</b>".' not found!' ;
    }

    //execute when static method not found
    public static function __callStatic(string $name, array $arguments)
    {
        echo 'static method '."<b>".$name."</b>".' not found!' ;
    }

    //execute when property not found
    public function __get(string $name)
    {
        echo "property "."<b>".$name."</b>"." not found!";
    }

    //execute when instance got printed
    public function __toString(): string
    {
        return 'try to connect to the '.'<b>'.$this->dbName.'</b>'.' database!';
    }
}

$myPdo = new UsePdo('localhost','root','','testone');
//echo $myPdo('user');
//$myPdo->insertToTable('user',['name'],['ahmad']);
//$myPdo->updateTable('user',['name'],['ajdar'],'id',1);

