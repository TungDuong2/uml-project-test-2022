<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/Database.php';
include_once '../class/Staffs.php';
 
$database = new Database();
$db = $database->getConnection();
 
$staffs = new Staffs($db);
 
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id)){ 
	
	$staffs->id = $data->id; 
	if(!empty($data->first_name)) $staffs->first_name = $data->first_name;
    if(!empty($data->last_name)) $staffs->last_name = $data->last_name;
    if(!empty($data->email)) $staffs->email = $data->email;
    if(!empty($data->supervisor_id)) $staffs->supervisor_id = $data->supervisor_id;
    if(!empty($data->department_id)) $staffs->department_id = $data->department_id;
    if(!empty($data->title)) $staffs->title = $data->title;
    if(!empty($data->sort)) $staffs->sort = $data->sort;
	
	
	if($staffs->update()){     
		http_response_code(200);   
		echo json_encode(array("message" => "Staff was updated."));
	}else{    
		http_response_code(503);     
		echo json_encode(array("message" => "Unable to update staffs."));
	}
	
} else {
	http_response_code(400);    
    echo json_encode(array("message" => "Unable to update staffs. Data is incomplete."));
}
?>