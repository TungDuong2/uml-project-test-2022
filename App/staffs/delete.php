<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
include_once '../config/Database.php';
include_once '../class/Staffs.php';
 
$database = new Database();
$db = $database->getConnection();
 
$staffs = new Staffs($db);
 
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->id)) {
	$staffs->id = $data->id;
	if($staffs->delete()){    
		http_response_code(200); 
		echo json_encode(array("message" => "Staff was deleted."));
	} else {    
		http_response_code(503);   
		echo json_encode(array("message" => "Unable to delete staff."));
	}
} else {
	http_response_code(400);    
    echo json_encode(array("message" => "Unable to delete staffs. Data is incomplete."));
}
?>