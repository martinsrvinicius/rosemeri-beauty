<?php

class User
{
    //db stuff
    private $conn;
    private $table = 'users';

    //post properties
    public $uniqueId;
    public $name;
    public $username;
    public $password;
    public $email;
    public $adm;
    public $terms;

    //constructor with db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        //create query
        $query = 'SELECT uniqueId, name, email, username, adm from '. $this->table;

        //prepare statement
        $stmt = $this->conn->prepare($query);
        //execute query
        $stmt->execute();
        return $stmt;
    }
    public function read_single()
    {
        $query = 'SELECT  uniqueId, name, email, username, adm from '. $this->table.
        ' WHERE uniqueId = ? LIMIT 1';

        //prepare statement
        $stmt = $this->conn->prepare($query);
        //binding param
        $stmt->bindParam(1, $this->uniqueId);
        //execute the query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->username = $row['username'];
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->adm = $row['adm'];
    }

    //LOGIN BY PASSWORD
    public function login()
    {
        $query = 'SELECT  uniqueId, username, name, email, adm from '. $this->table.
        ' WHERE email = :email and password = :password  LIMIT 1';

        //prepare statement
        $stmt = $this->conn->prepare($query);
        //binding param
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password',$this->password);
        //execute the query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $user_arr = array(
            'username'=> $row['username'],
            'name'=> $row['name'],
            'email'=> $row['email'],
            'adm'=> $row['adm'],
            'uniqueId'=> $row['uniqueId'],
        );
        $this->uniqueId = $row['uniqueId'];
        $this->username = $row['username'];
        $this->name = $row['name'];
        $this->email = $row['email'];
        $this->adm = $row['adm'];

        if($row > 0) {
            //echo json_encode($this->uniqueId);
            return $user_arr;
        }

        //print error if something goes wrong
        printf("Error %s \n", $stmt->error);
        return false;
    }

/*
    public function create()
    {
        //create query
        $query = 'INSERT INTO ' . $this->table . ' SET title = :title, body = :body, author = :author, category_id = :category_id, created_at = :created_at';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $date = new DateTime();
        $this->created_at = $date->format('Y-m-d H:i:s');

        //binding parameters
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':created_at', $this->created_at);
        //execute the query
        if ($stmt->execute()) {
            return true;
        }

        //print error if something goes wrong
        printf("Error %s \n", $stmt->error);
        return false;
    }

    //update post
    public function update()
    {
        //create query
        $query = 'UPDATE ' . $this->table .
            ' SET title = :title, body = :body, author = :author, category_id = :category_id, created_at = :created_at
            WHERE id = :id';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $date = new DateTime();
        $this->created_at = $date->format('Y-m-d H:i:s');

        //binding parameters
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':body', $this->body);
        $stmt->bindParam(':author', $this->author);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':created_at', $this->created_at);
        $stmt->bindParam(':id', $this->id);
        //execute the query
        if ($stmt->execute()) {
            return true;
        }

        //print error if something goes wrong
        printf("Error %s \n", $stmt->error);
        return false;
    }


    //delete function
    public function delete() {
        //create query
        $query = "DELETE FROM ". $this->table .
        ' WHERE id = :id';
        //prepare statement
        $stmt = $this->conn->prepare($query);
        //clean the data
        $this->id = htmlspecialchars(strip_tags($this->id));
        //binding the id parameter
        $stmt->bindParam(':id', $this->id);
         //execute the query
         if ($stmt->execute()) {
            return true;
        }

        //print error if something goes wrong
        printf("Error %s \n", $stmt->error);
        return false;

    }*/

}
?>