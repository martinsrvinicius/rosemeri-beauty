<?php
//headers
header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');
header('Content-Type: multipart/form-data');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access Control-Allow-Methods, Authorization, X-Requested-With');


//initializing our api
include_once('../../core/initialize.php');

//instantiate service
$service = new Service($db);

//get raw posted data
//$data = json_decode(file_get_contents('php://input'));

$service->uniqueId = $_POST['uniqueId'];
$service->titulo = $_POST['titulo'];
$service->descricao = $_POST['descricao'];
$service->duracao = $_POST['duracao'];
$service->preco = $_POST['preco'];

/*$service->titulo = $data->titulo; 
$service->uniqueId = $data->uniqueId; 
$service->descricao = $data->descricao; 
$service->preco = $data->preco; 
$service->duracao = $data->duracao;*/ 

//Update service info
if($service->update()) {
    echo json_encode(array($res['status'] = true));
} else {
    echo json_encode(array($res['status'] = false));
}