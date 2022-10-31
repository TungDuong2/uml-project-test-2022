<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/Database.php';
include_once '../class/Staffs.php';
 
$database = new Database();
$db = $database->getConnection();
 
$staffs = new Staffs($db);
 
$data = json_decode(file_get_contents("php://input"));

if(!(empty($data->first_name) || empty($data->last_name) || empty($data->email) || 
    empty($data->supervisor_id) || empty($data->department_id) || 
    empty($data->title) || empty($data->sort))){    

    $staffs->first_name = $data->first_name;
    $staffs->last_name = $data->last_name;
    $staffs->email = $data->email;
    $staffs->supervisor_id = $data->supervisor_id;
    $staffs->department_id = $data->department_id;
    $staffs->title = $data->title;
    $staffs->sort = $data->sort;
    
    if($staffs->add()){         
        http_response_code(201);         
        echo json_encode(array("message" => "Staff has been successfully added."));
    } else{         
        http_response_code(503);        
        echo json_encode(array("message" => "Unable to add staff."));
    }
}else{    
    http_response_code(400);    
    echo json_encode(array("message" => "Unable to add staff. Data is incomplete."));
}
?>