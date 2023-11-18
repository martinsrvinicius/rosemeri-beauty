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

   
}
