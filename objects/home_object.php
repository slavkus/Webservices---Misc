<?php

class Home {

	private $conn;

    public $user;
	
    public function __construct($db)
    {
        $this->conn = $db;
    }
	
	private function strip_atributes ()
	{
		$this->user = htmlspecialchars(strip_tags($this->user));
	}
	
	public function getUserInterest()
    {
        $query = "SELECT * FROM Event WHERE Event.sport IN 
		(SELECT sport FROM Interest WHERE user = :user)";

        $stmt = $this->conn->prepare($query);

		$this->strip_atributes();

        $stmt->bindparam(":user", $this->user);
		
        $stmt->execute();
						
        return $stmt;
    }

}

?>
