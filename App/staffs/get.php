<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../config/Database.php';
include_once '../class/Staffs.php';

$database = new Database();
$db = $database->getConnection();
 
$staffs = new Staffs($db);

$staffs->id = (isset($_GET['id']) && $_GET['id']) ? $_GET['id'] : '0';
$json_pretty = (isset($_GET['json']) && $_GET['json']) ? $_GET['json'] : '0';

$result = $staffs->get();

if($result->num_rows > 0){    
    $staffRecords=array();
    $staffRecords["staffs"]=array(); 
	while ($staff = $result->fetch_assoc()) { 	
        extract($staff); 
        $staffDetails=array(
            "id" => $id,
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $email,
            "supervisor_id" => $supervisor_id,
            "department_id" => $department_id,
            "title" => $title,
            "sort" => $sort		
        ); 
       array_push($staffRecords["staffs"], $staffDetails);
    }    
    http_response_code(200);   
    if ($json_pretty == 0) {
        echo json_encode($staffRecords);
    } else {
        echo json_encode($staffRecords, JSON_PRETTY_PRINT);
    }
    
}else{     
    http_response_code(404);     
    echo json_encode(
        array("message" => "No staff found.")
    );
} 