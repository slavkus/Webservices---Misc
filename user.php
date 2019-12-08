<?php
require_once 'resource.php';
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
        $query = "SELECT * FROM user WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindparam(":id", $this->id);
		
		echo "Test poruka User";
		
		echo $stmt->queryString;
		
        $stmt->execute();
						
        return $stmt;
    }

}

?>
