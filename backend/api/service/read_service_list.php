<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initializing our api
include_once('../../core/initialize.php');

//instantiate service
$service = new Service($db);

//All services
$result = $service->read_list_options();
//get the row count
$num = $result->rowCount();

if ($num > 0) {
    $service_arr['service'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $service = array(
            'uniqueId' => $uniqueId,
            'titulo' => $titulo,
            'duracao' => $duracao,
            'descricao' => $descricao,
            'preco' => $preco,
            'foto' => empty($foto) ? '' : trim(base64_encode($foto))
        );
        array_push($service_arr['service'], $service);
    }
    //convert to JSON and output
    echo json_encode($service_arr);
} else {
    echo json_encode(array('error'=> ''));
}