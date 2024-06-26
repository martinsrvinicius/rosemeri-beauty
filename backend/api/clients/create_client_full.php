<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access Control-Allow-Methods, Authorization, X-Requested-With');


//initializing our api
include_once('../../core/initialize.php');

//instantiate client
$client = new Client($db);

//get raw posted data
$data = json_decode(file_get_contents('php://input'));

$client->name = $data->nome; 

$obj = (object) [
    'data_nasc'=>null,
    'email'=>null,
    'telefone'=>null,
    'whatsapp'=>null,
    'rua'=>null,
    'localidade'=>null,
    'zipcode'=>null,
    'pais'=>null,
];
$obj->data_nasc = $data->data_nasc;
$obj->email = $data->email;
$obj->telefone = $data->telefone;
$obj->whatsapp = $data->whatsapp;
$obj->rua = $data->rua;
$obj->localidade = $data->localidade;
$obj->zipcode = $data->zipcode;
$obj->pais = $data->pais;
//echo json_encode($client);
//create event on calendar
if($client->createFull($obj)) {
    echo json_encode(array($res['status'] = true));
} else {
    echo json_encode(array($res['status'] = false));
}