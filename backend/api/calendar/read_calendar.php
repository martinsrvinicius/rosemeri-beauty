<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initializing our api
include_once('../../core/initialize.php');

//instantiate calendar
$calendar = new Calendar($db);

//All events on calendar
$result = $calendar->read();
//get the row count
$num = $result->rowCount();

if ($num > 0) {
    $calendar_arr['calendar'] = array();
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $event = array(
            'uniqueId' => $uniqueId,
            'client' => $nome,
            'service' => $titulo,
            'dataHora' => $dataHora,
            'confirm' => $confirm,
            'price' => $preco,
            'duracao' => $duracao,
        );
        array_push($calendar_arr['calendar'], $event);
    }
    //convert to JSON and output
    echo json_encode($calendar_arr);
} else {
    echo json_encode(array('error'=> ''));
}