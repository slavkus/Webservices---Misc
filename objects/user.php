<?php

class User {

	private $conn;
    private $table_name = "user";

    public $id;
    public $username;
    public $email;
    public $first_name;
    public $last_name;
	public $date_of_birth;
	public $picture_url;
	
    public function __construct($db)
    {
        $this->conn = $db;
    }
	
	public function getUser()
    {
        $query = "SELECT * FROM User WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindparam(":id", $this->id);
				
        $stmt->execute();
						
        return $stmt;
    }
	
	public function getSimpleUserData () 
	{
		$query = "SELECT username, email, picture_url FROM User WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindparam(":id", $this->id);
				
        $stmt->execute();
						
        return $stmt;
	}
}

?>
