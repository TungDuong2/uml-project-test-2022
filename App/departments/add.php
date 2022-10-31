<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/Database.php';
include_once '../class/Departments.php';
 
$database = new Database();
$db = $database->getConnection();
 
$departments = new Departments($db);
 
$data = json_decode(file_get_contents("php://input"));

if(!(empty($data->department) || empty($data->sort))){    

    $departments->department = $data->department;
    $departments->sort = $data->sort;
    
    if($departments->add()){         
        http_response_code(201);         
        echo json_encode(array("message" => "Department has been successfully added."));
    } else{         
        http_response_code(503);        
        echo json_encode(array("message" => "Unable to add department."));
    }
}else{    
    http_response_code(400);    
    echo json_encode(array("message" => "Unable to add department. Data is incomplete."));
}
?>