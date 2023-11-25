<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access Control-Allow-Methods, Authorization, X-Requested-With');

//initializing our api
include_once('../../core/initialize.php');

//instantiate calendar
$calendar = new Calendar($db);

//get raw posted data
$data = json_decode(file_get_contents('php://input'));

$calendar->uniqueId = $data->uniqueId;
$calendar->confirm = $data->confirm;
$calendar->dataHora = $data->dataHora;

//echo json_encode($calendar);
 
if ($calendar->updateOnDrop()) {
    echo json_encode($res['status'] = true);

} else {
    echo json_encode($res['status']=false);
}