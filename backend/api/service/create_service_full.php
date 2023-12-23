<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access Control-Allow-Methods, Authorization, X-Requested-With');


//initializing our api
include_once('../../core/initialize.php');

//instantiate service
$service = new Service($db);

//get raw posted data
$data = json_decode(file_get_contents('php://input'));

$service->titulo = $data->titulo; 
$service->descricao = $data->descricao; 
$service->preco = $data->preco; 
$service->duracao = $data->duracao; 

//echo json_encode($client);
//create new service on option list
if($service->create()) {
    echo json_encode(array($res['status'] = true));
} else {
    echo json_encode(array($res['status'] = false));
}