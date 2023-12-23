<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access Control-Allow-Methods, Authorization, X-Requested-With');

//initializing our api
include_once('../../core/initialize.php');

//instantiate post
$service = new Service($db);

//get raw posted data
$data = json_decode(file_get_contents('php://input'));

$service->uniqueId = $data->uniqueId;
//Delete post
if ($service->delete()) {
    echo json_encode($res['status'] = true);
} else {
    echo json_encode($res['status']=false);
}

?>