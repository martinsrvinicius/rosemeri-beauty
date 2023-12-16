<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initializing our api
include_once('../../core/initialize.php');

//instantiate client
$client = new Client($db);

//All clients
$result = $client->readFull();
//get the row count
$num = $result->rowCount();

if ($num > 0) {
    $clients_arr['clientsFull'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $client = array(
            'uniqueId' => $uniqueId,
            'client' => $nome,
            'data_nasc' => $data_nasc,
            'telefone' => $telefone,
            'whatsapp' => $whatsapp,
            'nif' => $nif,
            'rua' => $rua,
            'localidade' => $localidade,
            'zipcode' => $zipcode,
            'pais' => $pais,
        );
        array_push($clients_arr['clientsFull'], $client);
    }
    //convert to JSON and output
    echo json_encode($clients_arr);
} else {
    echo json_encode(array('error'=> ''));
}