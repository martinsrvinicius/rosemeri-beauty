<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access Control-Allow-Methods, Authorization, X-Requested-With');


//initializing our api
include_once('../../core/initialize.php');

//instantiate calendar
$client = new Client($db);

//get raw posted data
$data = json_decode(file_get_contents('php://input'));

$client->name = $data->name; 

//echo json_encode($client);
//create event on calendar
if($client->create()) {
    echo json_encode(array($res['status'] = true));
} else {
    echo json_encode(array($res['status'] = false));
}