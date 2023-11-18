<?php

class Client
{
    private $conn;
    private $table = 'cliente';

    //Client properties
    public $uniqueId;
    public $name;
    


    //constructor with db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Read all clients
    public function read()
    {
        //create query
        $query = 'SELECT uniqueId, nome from `cliente`';

        //prepare statement
        $stmt = $this->conn->prepare($query);
        //execute query
        $stmt->execute();
        return $stmt;
    }

    //Insert new event on calendar
    public function create()
    {
        //create query
        $query = 'INSERT INTO `cliente`(uniqueId, nome) VALUES (:uniqueId, :nome)';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->uniqueId = htmlspecialchars(strip_tags($this->getUnique($this->name)));

        //binding parameters
        $stmt->bindParam(':uniqueId', $this->uniqueId);
        $stmt->bindParam(':nome', $this->name);

        if ($stmt->execute()) {
            return true;
        }

        //print error if something goes wrong
        printf("Error %s \n", $stmt->error);
        return false;
    }


    //Creates uniqueId 
    public function getUnique($userName)
    {
        $ini = substr($userName, 0, 3);
        $now = DateTime::createFromFormat('U.u', microtime(true));

        $string = $ini . $now->format("YmdHis.u");

        return $string;
    }
   
}
