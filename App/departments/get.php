<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../config/Database.php';
include_once '../class/Departments.php';

$database = new Database();
$db = $database->getConnection();
 
$departments = new Departments($db);

$departments->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';
// $json_request = (isset($_GET['json']) && $_GET['json']) ? $_GET['json'] : '0';

$result = $departments->get();
if($result->num_rows > 0){    
    $departmentRecords=array();
    $departmentRecords["departments"]=array(); 
	while ($department = $result->fetch_assoc()) { 	
        extract($department); 
        $departmentDetails=array(
            "id" => $id,
            "department" => $department,
            "sort" => $sort		
        ); 
       array_push($departmentRecords["departments"], $departmentDetails);
    }    
    http_response_code(200);     
    echo json_encode($departmentRecords, JSON_PRETTY_PRINT);
}else{     
    http_response_code(404);     
    echo json_encode(
        array("message" => "No department found.")
    );
} 