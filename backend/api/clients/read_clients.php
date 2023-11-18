<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initializing our api
include_once('../../core/initialize.php');

//instantiate calendar
$calendar = new Client($db);

//All clients
$result = $calendar->read();
//get the row count
$num = $result->rowCount();

if ($num > 0) {
    $clients_arr['clients'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $client = array(
            'uniqueId' => $uniqueId,
            'client' => $nome,
        );
        array_push($clients_arr['clients'], $client);
    }
    //convert to JSON and output
    echo json_encode($clients_arr);
} else {
    echo json_encode(array('error'=> ''));
}