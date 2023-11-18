<?php

class Calendar
{
    private $conn;
    private $table = 'agendamento';

    //calendar event properties
    public $uniqueId;
    public $dataHora;
    public $confirm;
    public $uniqueIdCliente;
    public $uniqueIdServico;


    //constructor with db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    //Read all calendar events
    public function read()
    {
        //create query
        $query = 'SELECT agendamento.uniqueId, agendamento.dataHora, cliente.nome, servico.titulo, servico.preco, agendamento.confirm FROM ((agendamento INNER JOIN cliente ON agendamento.uniqueIdCliente= cliente.uniqueId)
        INNER JOIN servico ON agendamento.uniqueIdServico = servico.uniqueId)';

        //prepare statement
        $stmt = $this->conn->prepare($query);
        //execute query
        $stmt->execute();
        return $stmt;
    }

    //Select events by service Id
    public function readByServiceId()
    {
        //create query
        $query = 'SELECT agendamento.uniqueId, agendamento.dataHora, cliente.nome, servico.titulo, servico.preco, agendamento.confirm FROM ((agendamento INNER JOIN cliente ON agendamento.uniqueIdCliente= cliente.uniqueId)
        INNER JOIN servico ON agendamento.uniqueIdServico = servico.uniqueId) WHERE agendamento.uniqueIdServico = :uniqueIdServico';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //binding param
        $stmt->bindParam(':uniqueIdServico', $this->uniqueIdServico);
        //execute the query
        $stmt->execute();
        return $stmt;

    }

    //Insert new event on calendar
    public function create()
    {
        //create query
        $query = 'INSERT INTO `agendamento`(confirm, uniqueId, uniqueIdCliente, uniqueIdServico, dataHora) VALUES (:confirm, :uniqueId, :uniqueIdCliente, :uniqueIdServico, :dataHora)';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->confirm = htmlspecialchars(strip_tags($this->confirm));
        $this->uniqueIdCliente = htmlspecialchars(strip_tags($this->uniqueIdCliente));
        $this->uniqueIdServico = htmlspecialchars(strip_tags($this->uniqueIdServico));
        $this->dataHora = htmlspecialchars(strip_tags(date('Y-m-d H:i:s', strtotime($this->dataHora))));
        $this->uniqueId = htmlspecialchars(strip_tags($this->getUnique($this->dataHora)));

        //binding parameters
        $stmt->bindParam(':confirm', $this->confirm);
        $stmt->bindParam(':uniqueId', $this->uniqueId);
        $stmt->bindParam(':uniqueIdCliente', $this->uniqueIdCliente);
        $stmt->bindParam(':uniqueIdServico', $this->uniqueIdServico);
        $stmt->bindParam(':dataHora', $this->dataHora);

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


    //update event on drop calendar
    public function updateOnDrop()
    {
        $query = 'UPDATE `agendamento` SET confirm=:confirm, 
        dataHora=:dataHora WHERE uniqueId=:uniqueId';

        //prepare statement
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->confirm = htmlspecialchars(strip_tags($this->confirm));
        $this->dataHora = htmlspecialchars(strip_tags(date('Y-m-d H:i:s', strtotime($this->dataHora))));
        $this->uniqueId = htmlspecialchars(strip_tags($this->uniqueId));

        //binding parameters
        $stmt->bindParam(':confirm', $this->confirm);
        $stmt->bindParam(':uniqueId', $this->uniqueId);
        $stmt->bindParam(':dataHora', $this->dataHora);

        //execute the query
        if ($stmt->execute()) {
            return true;
        }

        //print error if something goes wrong
        printf("Error %s \n", $stmt->error);
        return false;
    }

    public function confirm()
    {
        $query = 'UPDATE `agendamento` SET confirm=:confirm WHERE uniqueId=:uniqueId';

        $stmt = $this->conn->prepare($query);
        //clear data
        $this->confirm = htmlspecialchars(strip_tags($this->confirm));

        //binding param
        $stmt->bindParam(":uniqueId", $this->uniqueId);
        $stmt->bindParam(":confirm", $this->confirm);
        //execute
        if ($stmt->execute()) {
            return true;
        }

        //print error if something goes wrong
        printf("Error %s \n", $stmt->error);
        return false;
    }

    //Delete event on calendar
    public function delete()
    {
        $query = "DELETE FROM agendamento WHERE uniqueId=?";

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
