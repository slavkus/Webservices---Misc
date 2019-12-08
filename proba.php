<?php

require_once("database.php");
Database db = new Database();
$connection = db->getConnection();
$sql = "SELECT * FROM Sport";
$connection->query($sql);




?>