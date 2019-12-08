<?php

class Chat {

	private $conn;
    private $table_name = "user_by_event";

	public $id;
    public $user;
    public $event;
    public $message;
	public $date_time;
    public $approved;
	
    public function __construct($db)
    {
        $this->conn = $db;
    }
	
	private function strip_atributes ()
	{
		$this->id = htmlspecialchars(strip_tags($this->id));
		$this->user = htmlspecialchars(strip_tags($this->user));
		$this->event = htmlspecialchars(strip_tags($this->event));
		$this->message = htmlspecialchars(strip_tags($this->message));
		$this->date_time = htmlspecialchars(strip_tags($this->date_time));
		$this->approved = htmlspecialchars(strip_tags($this->approved));
	}
	
	public function getChat()
	{
		$query = "SELECT message FROM Chat, user_by_event WHERE Chat.event = :event AND
		Chat.user = :user AND user_by_event.approved = :approved AND Chat.user = user_by_event.user";
		
		$stmt = $this->conn->prepare($query);

		$this->strip_atributes();
		
		$stmt->bindparam(":user", $this->user);
		$stmt->bindparam(":event", $this->event);
		$stmt->bindparam(":approved",$this->approved);
		
        $stmt->execute();
						
        return $stmt;
	}

}

?>
