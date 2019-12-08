<?php

class Create_event {

	private $conn;

    public $id_user;
	public $id_event;
    public $name;
    public $date_var;
    public $time_var;
    public $longitude;
	public $latitude;
	public $minimum_players;
	public $maximum_players;
	public $description;
	public $address;
	public $sport;
	public $organisator;
	
    public function __construct($db)
    {
        $this->conn = $db;
    }
	
	private function strip_atributes ()
	{
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->date_var = htmlspecialchars(strip_tags($this->date_var));
        $this->time_var = htmlspecialchars(strip_tags($this->time_var));
        $this->longitude = htmlspecialchars(strip_tags($this->longitude));
        $this->latitude = htmlspecialchars(strip_tags($this->latitude));
        $this->minimum_players = htmlspecialchars(strip_tags($this->minimum_players));
        $this->maximum_players = htmlspecialchars(strip_tags($this->maximum_players));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->sport = htmlspecialchars(strip_tags($this->sport));
		$this->organisator = htmlspecialchars(strip_tags($this->organisator));
	}
	
	public function getUser()
    {
        $query = "SELECT id FROM user WHERE id_user = :id";

        $stmt = $this->conn->prepare($query);

        $this->id_user = htmlspecialchars(strip_tags($this->id_user));

        $stmt->bindparam(":id", $this->id_user);
				
        $stmt->execute();
						
        return $stmt;
    }
	
	public function insertEvent()
    {
        $query = "INSERT INTO Event
		(id, name, date, time, longitude, latitude, minimum_players, 
		maximum_players, description, address, sport, organisator) VALUES
		(:id, :name, :date, :time, :longitude, :latitude, :minimum_players, 
		:maximum_players, :description, :address, :sport, :organisator)";

        $stmtCreate = $this->conn->prepare($query);

		$this->strip_atributes();
		$stmtCreate->bindParam(":id", $this->id);
		$stmtCreate->bindParam(":name", $this->name);
		$stmtCreate->bindParam(":date", $this->date_var);
		$stmtCreate->bindParam(":time", $this->time_var);
		$stmtCreate->bindParam(":longitude", $this->longitude);
		$stmtCreate->bindParam(":latitude", $this->latitude);
		$stmtCreate->bindParam(":minimum_players", $this->minimum_players);
		$stmtCreate->bindParam(":maximum_players", $this->maximum_players);
		$stmtCreate->bindParam(":description", $this->description);
		$stmtCreate->bindParam(":address", $this->address);
		$stmtCreate->bindParam(":sport", $this->sport);
		$stmtCreate->bindParam(":organisator", $this->organisator);
 		
        $stmtCreate->execute();
		
		return $stmtCreate;
    }

    public function updateEvent($id_event)
    {
        $query = "UPDATE Event SET name = :name, 
		date = :date, date = :time, longitude = :longitude, latitude = :latitude, 
		minimum_players = :minimum_players, maximum_players = :maximum_players, 
		description = :description, sport = :sport, address = :address FROM
		event, user WHERE id_event = :id_event 
        ";

        $stmtUpdate = $this->conn->prepare($query);

		$this->strip_atributes();
		
		$stmtUpdate->bindParam(":id_event", $this->id_event);
		$stmtUpdate->bindParam(":name", $this->name);
		$stmtUpdate->bindParam(":date", $this->date_var);
		$stmtUpdate->bindParam(":time", $this->time_var);
		$stmtUpdate->bindParam(":longitude", $this->longitude);
		$stmtUpdate->bindParam(":latitude", $this->latitude);
		$stmtUpdate->bindParam(":minimum_players", $this->minimum_players);
		$stmtUpdate->bindParam(":maximum_players", $this->maximum_players);
		$stmtUpdate->bindParam(":description", $this->description);
		$stmtUpdate->bindParam(":address", $this->address);
		$stmtUpdate->bindParam(":sport", $this->sport);
		$stmtUpdate->bindParam(":organisator", $this->organisator);

        $stmtUpdate->execute();
		
		return $stmtUpdate;
        
    }

}

?>
