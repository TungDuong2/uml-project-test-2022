<?php
class Departments{   

    public $id;
    public $department;   
	public $sort; 
    private $conn;
	
    public function __construct($db){
        $this->conn = $db;
    }	
	
	function get(){	
		// if get the id from the input
		if($this->id) {
			$sql = "SELECT * FROM departments WHERE id = ?";
			$stmt = $this->conn->prepare($sql);
			$stmt->bind_param("i", $this->id);					
		} else { // print all records
			$sql = "SELECT * FROM departments ORDER BY id";
			$stmt = $this->conn->prepare($sql);		
		}		
		$stmt->execute();			
		$result = $stmt->get_result();		
		return $result;	
	}
	
	function add(){
		// remove the spicial chars
		$this->department = htmlspecialchars(strip_tags($this->department));
		$this->sort = htmlspecialchars(strip_tags($this->sort));
		
		// need to modify auto increment for the id
		$sql = "ALTER TABLE departments
				MODIFY id int(11) NOT NULL AUTO_INCREMENT;";
		$stmt = $this->conn->prepare($sql);
		$stmt->execute();
		
		$stmt = $this->conn->prepare("
			INSERT INTO departments (department, sort)
			VALUES(?,?)");
		
		$stmt->bind_param("si", $this->department, $this->sort);
		
		if($stmt->execute()){
			return true;
		}
		
		return false;		 
	}
		
	function update(){
	 
		$this->id = htmlspecialchars(strip_tags($this->id));
		if(!empty($this->department)) $this->department = htmlspecialchars(strip_tags($this->department));
		if(!empty($this->sort)) $this->sort = htmlspecialchars(strip_tags($this->sort));
		
		settype($this->department, string); 
		settype($this->sort, int);

		$check_department = !empty($this->department) ? 1 : 0;
		$check_sort = !empty($this->sort) ? 1 : 0;

		$sql = "UPDATE departments 
				SET department = IF(".$check_department.", '$this->department', department),
					sort = IF(".$check_sort.", $this->sort, sort)
				WHERE id = $this->id";
		$stmt = $this->conn->prepare($sql);		

		if($stmt->execute()){
			return true;
		}
	 
		return false;
	}
	
	function delete(){
		
		$this->id = htmlspecialchars(strip_tags($this->id));
		
		settype($this->id, int);

		$stmt = $this->conn->prepare("DELETE FROM departments WHERE id = ?");

		$stmt->bind_param("i", $this->id);
		
		if($stmt->execute()){
			return true;
		}
	 
		return false;		 
	}
}
?>