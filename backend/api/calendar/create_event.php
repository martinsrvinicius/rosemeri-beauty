<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access Control-Allow-Methods, Authorization, X-Requested-With');


//initializing our api
include_once('../../core/initialize.php');

//instantiate calendar
$calendar = new Calendar($db);

//get raw posted data
$data = json_decode(file_get_contents('php://input'));

/*
$calendar->confirm = $data->confirm; 
//$calendar->uniqueId = $data->uniqueId; 
$calendar->uniqueIdCliente = $data->uniqueIdCliente; 
$calendar->uniqueIdServico = $data->uniqueIdServico[0]; 
$calendar->dataHora = $data->dataHora; 
*/

//create event on calendar
$obj = (object)[
    'confirm' => null,
    'uniqueIdCliente' => null,
    'uniqueIdServico' => null,
    'dataHora' => null,
];
$res['create'] = array();
$status = null;
for($i=0; $i < count($data->uniqueIdServico); $i++){
    $obj->confirm = $data->confirm; 
    $obj->uniqueIdCliente = $data->uniqueIdCliente; 
    $obj->uniqueIdServico = $data->uniqueIdServico[$i]; 
    $obj->dataHora = $data->dataHora[$i]; 
    //echo json_encode($obj);
    if($calendar->create_event($obj)) {
        $status = true;
    } else {
        $status = false;
    }
}

array_push($res['create'], $status);
echo json_encode($res);


