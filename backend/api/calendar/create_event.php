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

$calendar->confirm = $data->confirm; 
//$calendar->uniqueId = $data->uniqueId; 
$calendar->uniqueIdCliente = $data->uniqueIdCliente; 
$calendar->uniqueIdServico = $data->uniqueIdServico; 
$calendar->dataHora = $data->dataHora; 

echo json_encode($calendar);
//create event on calendar
if($calendar->create()) {
    echo json_encode(array('message'=> 'Agendamento criado com sucesso'));
} else {
    echo json_encode(array('message'=> 'Não foi possível criar agendamento'));
}