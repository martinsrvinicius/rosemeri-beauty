<?php

class Service
{
    private $conn;
    private $table = 'servico';

    //Service properties
    public $uniqueId;
    public $titulo;
    public $descricao;
    public $preco;
    


    //constructor with db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Read all services options for scheduling
    public function read_list_options()
    {
        //create query
        $query = 'SELECT uniqueId, titulo from `servico`';

        //prepare statement
        $stmt = $this->conn->prepare($query);
        //execute query
        $stmt->execute();
        return $stmt;
    }


     //Insert new service on option list
     public function create_item_option($titulo)
     {
         //create query
         $query = 'INSERT INTO `servico`(uniqueId, titulo) VALUES (:uniqueId, :titulo)';
         //prepare statement
         $stmt = $this->conn->prepare($query);
         //clean data
         $this->titulo = htmlspecialchars(strip_tags($titulo));
         $this->uniqueId = htmlspecialchars(strip_tags($this->getUnique($titulo)));
 
         //binding parameters
         $stmt->bindParam(':uniqueId', $this->uniqueId);
         $stmt->bindParam(':titulo', $titulo);
 
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
