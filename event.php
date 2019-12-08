<?php
require_once 'resource.php';

class Event {

	private $conn;
    private $table_name = "event";

    public $id;
    public $name;
    public $date;
    public $time;
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
	
	public function getEvent()
    {
        $query = "SELECT * FROM event WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindparam(":id", $this->id);
		
		echo "Test poruka Event";
		
		echo $stmt->queryString;
		
        $stmt->execute();
					
        return $stmt;
    }

}

?>
