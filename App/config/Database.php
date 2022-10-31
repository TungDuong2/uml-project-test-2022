<?php
class Database{
	
	private $servername = '10.6.1.2';
	private $username = 'user';
	private $password = 'pass';
	private $database = 'mydb'; 
    
    public function getConnection(){		
		$conn = new mysqli($this->servername, $this->username, $this->password, $this->database);
		if($conn->connect_error){
			die("Error failed to connect to MySQL: " . $conn->connect_error);
		} else {
			return $conn;
		}
    }
}
?>