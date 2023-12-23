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
    public $duracao;



    //constructor with db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Read all services options for scheduling
    public function read_list_options()
    {
        //create query
        $query = 'SELECT uniqueId, titulo, duracao, descricao, preco from `servico`';

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

    //Insert new service full info
    public function create()
    {
        //create query
        $query = 'INSERT INTO `servico`(uniqueId, titulo, descricao, duracao, preco) VALUES (:uniqueId, :titulo, :descricao, :duracao, :preco)';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->preco = htmlspecialchars(strip_tags($this->preco));
        $this->duracao = htmlspecialchars(strip_tags($this->duracao));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->uniqueId = htmlspecialchars(strip_tags($this->getUnique($this->titulo)));

        //binding parameters
        $stmt->bindParam(':uniqueId', $this->uniqueId);
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':duracao', $this->duracao);
        $stmt->bindParam(':preco', $this->preco);

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


    //update service 
    public function update()
    {
        $query = 'UPDATE `servico` SET titulo=:titulo, 
    descricao=:descricao, preco=:preco, duracao=:duracao WHERE uniqueId=:uniqueId';

        //prepare statement
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->duracao = htmlspecialchars(strip_tags($this->duracao));
        $this->preco = htmlspecialchars(strip_tags($this->preco));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->uniqueId = htmlspecialchars(strip_tags($this->uniqueId));

        //binding parameters
        $stmt->bindParam(':uniqueId', $this->uniqueId);
        $stmt->bindParam(':titulo', $this->titulo);
        $stmt->bindParam(':descricao', $this->descricao);
        $stmt->bindParam(':duracao', $this->duracao);
        $stmt->bindParam(':preco', $this->preco);

        //execute the query
        if ($stmt->execute()) {
            return true;
        }

        //print error if something goes wrong
        printf("Error %s \n", $stmt->error);
        return false;
    }

    //Delete service
    public function delete()
    {
        $query = "DELETE FROM `servico` WHERE uniqueId=?";

        $stmt = $this->conn->prepare($query);
        $this->uniqueId = htmlspecialchars(strip_tags($this->uniqueId));

        $stmt->bindParam(1, $this->uniqueId);
        if ($stmt->execute()) {
            return true;
        }
        //print error if something goes wrong
        printf("Error %s \n", $stmt->error);
        return false;

    }

}
