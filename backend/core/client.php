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

    //Insert client 
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

    //Insert new client full info
    public function createFull($obj)
    {
        //create query
        $query = 'INSERT INTO `cliente`(uniqueId, nome, data_nasc, telefone, whatsapp, rua, localidade, zipcode, pais) VALUES (:uniqueId, :nome, :data_nasc, :telefone, :whatsapp, :rua, :localidade, :zipcode, :pais)';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //clean data
       $pais = htmlspecialchars(strip_tags($obj->pais));
        $zipcode = htmlspecialchars(strip_tags($obj->zipcode));
        $localidade = htmlspecialchars(strip_tags($obj->localidade));
        $rua = htmlspecialchars(strip_tags($obj->rua));
        $whatsapp = htmlspecialchars(strip_tags($obj->whatsapp));
        $telefone = htmlspecialchars(strip_tags($obj->telefone));
        $data_nasc = htmlspecialchars(strip_tags(date('Y-m-d', strtotime($obj->data_nasc))));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->uniqueId = htmlspecialchars(strip_tags($this->getUnique($this->name)));

        //binding parameters
        $stmt->bindParam(':pais', $pais);
        $stmt->bindParam(':zipcode', $zipcode);
        $stmt->bindParam(':localidade', $localidade);
        $stmt->bindParam(':rua', $rua);
        $stmt->bindParam(':whatsapp', $whatsapp);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':data_nasc', $data_nasc);
        $stmt->bindParam(':uniqueId', $this->uniqueId);
        $stmt->bindParam(':nome', $this->name);

        if ($stmt->execute()) {
            return true;
        }

        //print error if something goes wrong
        printf("Error %s \n", $stmt->error);
        return false;
    }
    

//update client 
public function update($obj)
{
    $query = 'UPDATE `cliente` SET nome=:nome, 
    data_nasc=:data_nasc, telefone=:telefone, whatsapp=:whatsapp, rua=:rua, localidade=:localidade, zipcode=:zipcode, pais=:pais WHERE uniqueId=:uniqueId';

    //prepare statement
    $stmt = $this->conn->prepare($query);
    //clean data
    $pais = htmlspecialchars(strip_tags($obj->pais));
    $zipcode = htmlspecialchars(strip_tags($obj->zipcode));
    $localidade = htmlspecialchars(strip_tags($obj->localidade));
    $rua = htmlspecialchars(strip_tags($obj->rua));
    $whatsapp = htmlspecialchars(strip_tags($obj->whatsapp));
    $telefone = htmlspecialchars(strip_tags($obj->telefone));
    $data_nasc = htmlspecialchars(strip_tags(date('Y-m-d', strtotime($obj->data_nasc))));
    $this->name = htmlspecialchars(strip_tags($this->name));
    $this->uniqueId = htmlspecialchars(strip_tags($this->uniqueId));

    //binding parameters
    $stmt->bindParam(':pais', $pais);
    $stmt->bindParam(':zipcode', $zipcode);
    $stmt->bindParam(':localidade', $localidade);
    $stmt->bindParam(':rua', $rua);
    $stmt->bindParam(':whatsapp', $whatsapp);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':data_nasc', $data_nasc);
    $stmt->bindParam(':uniqueId', $this->uniqueId);
    $stmt->bindParam(':nome', $this->name);

    //execute the query
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
