<?php
class Staffs{   

    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $supervisor_id;
    public $department_id;   
    public $title; 
	public $sort; 
    private $conn;
	
    public function __construct($db){
        $this->conn = $db;
    }	
	
	function get(){	
		// if get the id from the input
		if($this->id) {
			$sql = "SELECT * FROM staff WHERE id = ?";
			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("i", $this->id);					
		} else { // print all records
			$sql = "SELECT * FROM staff ORDER BY id";
			$stmt = $this->conn->prepare($sql);		
		}		
		$stmt->execute();			
		$result = $stmt->get_result();		
		return $result;	
	}
	
	function add(){
		// remove the spicial chars
		$this->first_name = htmlspecialchars(strip_tags($this->first_name));
		$this->last_name = htmlspecialchars(strip_tags($this->last_name));
		$this->email = htmlspecialchars(strip_tags($this->email));
		$this->supervisor_id = htmlspecialchars(strip_tags($this->supervisor_id));
		$this->department_id = htmlspecialchars(strip_tags($this->department_id));
		$this->title = htmlspecialchars(strip_tags($this->title));
		$this->sort = htmlspecialchars(strip_tags($this->sort));
		
		// need to modify auto increment for the id
		$sql = "ALTER TABLE staff
				MODIFY id int(11) NOT NULL AUTO_INCREMENT;";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		
		$stmt = $this->conn->prepare("
			INSERT INTO staff (first_name, last_name, email, supervisor_id, department_id, title, sort)
			VALUES(?,?,?,?,?,?,?)");
		
		$stmt->bind_param("sssiisi", $this->first_name, $this->last_name, $this->email, 
							$this->supervisor_id, $this->department_id, $this->title, $this->sort);
		
		if($stmt->execute()){
			return true;
		}
		
		return false;		 
	}
		
	function update(){
	 
		$this->id = htmlspecialchars(strip_tags($this->id));
		if(!empty($this->first_name)) $this->first_name = htmlspecialchars(strip_tags($this->first_name));
		if(!empty($this->last_name)) $this->last_name = htmlspecialchars(strip_tags($this->last_name));
		if(!empty($this->email)) $this->email = htmlspecialchars(strip_tags($this->email));
		if(!empty($this->supervisor_id)) $this->supervisor_id = htmlspecialchars(strip_tags($this->supervisor_id));
		if(!empty($this->department_id)) $this->department_id = htmlspecialchars(strip_tags($this->department_id));
		if(!empty($this->title)) $this->title = htmlspecialchars(strip_tags($this->title));
		if(!empty($this->sort)) $this->sort = htmlspecialchars(strip_tags($this->sort));
		
		settype($this->first_name, string); 
		settype($this->last_name, string);
		settype($this->email, string);
		settype($this->supervisor_id, int);
		settype($this->department_id, int);
		settype($this->title, string);
		settype($this->sort, int);

		// Because "COALESCE" does not work stable in this version, then I have to use "IF" instead.
		$check_first_name = !empty($this->first_name) ? 1 : 0;
		$check_last_name = !empty($this->last_name) ? 1 : 0;
		$check_email = !empty($this->email) ? 1 : 0;
		$check_supervisor_id = !empty($this->supervisor_id) ? 1 : 0;
		$check_department_id = !empty($this->department_id) ? 1 : 0;
		$check_title = !empty($this->title) ? 1 : 0;
		$check_sort = !empty($this->sort) ? 1 : 0;

		$sql = "UPDATE staff 
				-- SET first_name = COALESCE($this->first_name, first_name),
				SET first_name = IF(".$check_first_name.", '$this->first_name', first_name),
					last_name = IF(".$check_last_name.", '$this->last_name', last_name),
					email = IF(".$check_email.", '$this->email', email),
					supervisor_id = IF(".$check_supervisor_id.", $this->supervisor_id, supervisor_id),
					department_id = IF(".$check_department_id.", $this->department_id, department_id),
					title = IF(".$check_title.", '$this->title', title),
					sort = IF(".$check_sort.", $this->sort, sort)
				WHERE id = $this->id";
		$stmt = $this->conn->prepare($sql);
	 
		// $stmt->bind_param("sssiisii", $this->first_name, $this->last_name, $this->email, 
		// 					$this->supervisor_id, $this->department_id, $this->title, $this->sort, $this->id);
		// $stmt->bind_param("i", $this->id);
		

		if($stmt->execute()){
			return true;
		}
	 
		return false;
	}
	
	function delete(){
		
		$stmt = $this->conn->prepare("DELETE FROM staff WHERE id = ?");
			
		$this->id = htmlspecialchars(strip_tags($this->id));
	 
		$stmt->bind_param("i", $this->id);
	 
		if($stmt->execute()){
			return true;
		}
	 
		return false;		 
	}
}
?>