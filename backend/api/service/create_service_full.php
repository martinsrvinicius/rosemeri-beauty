<?php
//headers
header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');
header('Content-Type: multipart/form-data');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access Control-Allow-Methods, Authorization, X-Requested-With');


//initializing our api
include_once('../../core/initialize.php');

//instantiate service
$service = new Service($db);

//get raw posted data
//$data = json_decode(file_get_contents('php://input'));

if (count($_FILES) > 0) {
    if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
        $type = $_FILES['foto']['type'];
        $imgData = file_get_contents($_FILES['foto']['tmp_name']);
        $imageProperties = getimageSize($_FILES['foto']['tmp_name']);
        $service->foto = $imgData;
    }
}

$service->titulo = $_POST['titulo'];
$service->descricao = $_POST['descricao'];
$service->duracao = $_POST['duracao'];
$service->preco = $_POST['preco'];

/*$service->titulo = $data->titulo;
$service->descricao = $data->descricao;
$service->preco = $data->preco;
$service->duracao = $data->duracao;*/

//echo (  $service->foto);
//create new service on option list
if ($service->create()) {
    echo json_encode(array($res['status'] = true));
} else {
    echo json_encode(array($res['status'] = false));
}