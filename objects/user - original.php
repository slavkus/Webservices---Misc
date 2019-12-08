<?php

class User
{
    private $conn;
    private $table_name = "user";

    public $id;
    public $username;
    public $email;
    public $updated_at;
    public $deleted_at;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getIdUsingEmail()
    {
        $query = "
		SELECT id, username, email, updated_at, deleted_at FROM user WHERE email = :email";

        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindparam(":email", $this->email);

        $stmt->execute();

        return $stmt;
    }

    public function insert()
    {
        $query = "
        INSERT INTO " . $this->table_name . "
		(username, email, updated_at, deleted_at) VALUES
		(:username, :email, NULL, NULL)
        ";

        // prepare query
        $stmtCreate = $this->conn->prepare($query);

        // sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind values
        $stmtCreate->bindParam(":username", $this->username);
        $stmtCreate->bindParam(":email", $this->email);

        // execute query
        if($stmtCreate->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function update()
    {
        $query = "
        UPDATE " . $this->table_name . " SET deleted_at = NULL, updated_at = CURRENT_TIMESTAMP WHERE 
        username = :username AND email = :email
        ";

        // prepare query
        $stmtUpdate = $this->conn->prepare($query);

        // sanitize
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind values
        $stmtUpdate->bindParam(":username", $this->username);
        $stmtUpdate->bindParam(":email", $this->email);

        // execute query
        if($stmtUpdate->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}