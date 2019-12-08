<?php

class User_by_event {

	private $conn;
    private $table_name = "user_by_event";

    public $user;
    public $event;
    public $application_timestamp;
    public $score;
    public $approved;
	public $decision_timestamp;
	
    public function __construct($db)
    {
        $this->conn = $db;
    }
	
	private function strip_atributes ()
	{
		$this->user = htmlspecialchars(strip_tags($this->user));
		$this->event = htmlspecialchars(strip_tags($this->event));
		$this->approved = htmlspecialchars(strip_tags($this->approved));
		$this->score = htmlspecialchars(strip_tags($this->score));
	}
	
	public function getRatings() 
	{
		$query = "SELECT AVG(score) FROM user_by_event WHERE user = :user;";
		
        $stmt = $this->conn->prepare($query);

		$this->strip_atributes();

        $stmt->bindparam(":user", $this->user);
		
        $stmt->execute();
						
        return $stmt;
	}
	
	public function getAllUserByEvent()
    {
        $query = "SELECT * FROM user_by_event WHERE user = :user 
		AND event = :event";
		
        $stmt = $this->conn->prepare($query);

		$this->strip_atributes();

        $stmt->bindparam(":user", $this->user);
		$stmt->bindparam(":event", $this->event);
		
        $stmt->execute();
						
        return $stmt;
    }
	
	public function getApprovedUserByEvent()
    {
        $query = "SELECT * FROM user_by_event WHERE user = :user AND event = :event 
		AND approved = :approved";
        $stmt = $this->conn->prepare($query);

		$this->strip_atributes();

        $stmt->bindparam(":user", $this->user);
		$stmt->bindparam(":event", $this->event);
		$stmt->bindparam(":approved",$this->approved);
		
        $stmt->execute();
						
        return $stmt;
    }
	
	public function insertUserInEvent () {
		 
		$query = "INSERT INTO user_by_event
		(user, event, application_timestamp, score, approved, decision_timestamp) VALUES
		(:user, :event, now(), :score, :approved, now())";
		
		$stmtInsert = $this->conn->prepare($query);

		$this->strip_atributes();
		
		
		$stmtInsert->bindParam(":user", $this->user);
		$stmtInsert->bindParam(":event", $this->event);
		//$stmtInsert->bindParam(":application_timestamp", $this->application_timestamp);
		$stmtInsert->bindParam(":score", $this->score);
		$stmtInsert->bindParam(":approved", $this->approved);
		//$stmtInsert->bindParam(":decision_timestamp", $this->decision_timestamp);
	
		$stmtInsert->execute();
		
		return $stmtInsert;
	
	}
	
	public function deleteUserInEvent () {
		 
		$query = "DELETE FROM user_by_event WHERE user = :user AND 
		event = :event";
		
		$stmtDelete = $this->conn->prepare($query);

		$this->strip_atributes();
		
		$stmtDelete->bindParam(":user", $this->user);
		$stmtDelete->bindParam(":event", $this->event);
		
		$stmtDelete->execute();
		
		return $stmtDelete;
	
	}
	
	public function getApprovedUsers()
	{
		$query = "SELECT user FROM user_by_event WHERE event = :event 
		AND approved = :approved";
		
		$stmt = $this->conn->prepare($query);

		$this->strip_atributes();

		$stmt->bindparam(":event", $this->event);
		$stmt->bindparam(":approved",$this->approved);
		
        $stmt->execute();
						
        return $stmt;
	}
	

}

?>
