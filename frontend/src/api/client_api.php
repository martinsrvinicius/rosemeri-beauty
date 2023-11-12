<?php
include 'db_connection.php';
$conn = OpenConPDO();
if (isset($_SERVER['HTTP_ORIGIN'])) {
    //Decide if the origin in $_SERVER['HTTP_ORIGIN] is one you want to allow, and if so:
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 1000');
}
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])) {
        //may also be using PUT, PATCH, HEAD etc
        header("Access-Control-Allow-Headers: Accept, Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token, Authorization");
    }
    exit(0);
}
//$res = array();
$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

//Insert new client
if ($action == "addNewClient") {
    $res = array();
    $name = $_POST['name'];
    $uniqueId = getUnique($name);
    ;
  
    $sql = $conn->prepare("INSERT INTO `cliente`(uniqueId, nome) VALUES (:uniqueId, :name)");

    $sql->execute(
        array(
            ':uniqueId' => $uniqueId,
            ':name' => $name,
        )
    );

    $result = $sql->rowCount();
    if ($result > 0) {
        $res['error'] = false;
        $res['message'] = "Usuário inserido com sucesso!";
    } else {
        $res['error'] = true;
        $res['message'] = "Não foi possível inserir o agendamento. Tente novamente!";
    }
}

function getUnique($userName)
{
    $ini = substr($userName, 0, 3);
    $now = DateTime::createFromFormat('U.u', microtime(true));

    $string = $ini . $now->format("YmdHis.u");

    return $string;
}


header("Content-type: application/json");
echo json_encode($res);
die();