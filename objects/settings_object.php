<?php

class Settings {

	private $conn;

    public $sport;
	public $user;

	
    public function __construct($db)
    {
        $this->conn = $db;
    }
	
	private function strip_atributes ()
	{
		$this->sport = htmlspecialchars(strip_tags($this->sport));
		$this->user = htmlspecialchars(strip_tags($this->user));
	}
	
	public function insertSettings()
    {
        $query = "INSERT INTO Interest (sport, user) VALUES (:sport, :user)";

        $stmtCreate = $this->conn->prepare($query);

		$this->strip_atributes();

		$stmtCreate->bindParam(":sport", $this->sport);
		$stmtCreate->bindParam(":user", $this->user);
 		
        $stmtCreate->execute();
		
		return $stmtCreate;
    }

    public function updateSettings()
    {
        $query = "UPDATE Interest SET sport = :sport WHERE user = :user";

        $stmtUpdate = $this->conn->prepare($query);

		$this->strip_atributes();
		
		$stmtUpdate->bindParam(":sport", $this->sport);
		$stmtUpdate->bindParam(":user", $this->user);

        $stmtUpdate->execute();
		
		return $stmtUpdate;
        
    }
	
	public function deleteSettings () {
		 
		$query = "DELETE FROM Interest WHERE sport = :sport AND 
		user = :user";
		
		$stmtDelete = $this->conn->prepare($query);

		$this->strip_atributes();
		
		$stmtDelete->bindParam(":sport", $this->sport);
		$stmtDelete->bindParam(":user", $this->user);
		
		$stmtDelete->execute();
		
		return $stmtDelete;
	
	}

}

?>
