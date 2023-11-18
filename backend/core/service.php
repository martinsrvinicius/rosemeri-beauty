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

   
}
