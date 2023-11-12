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

if ($action == "getCalendar") {
    $res = array();
    //$sql = "SELECT * FROM agendamento";
    $sql = "SELECT agendamento.uniqueId, agendamento.dataHora, cliente.nome, servico.titulo, servico.preco, agendamento.confirm
    FROM ((agendamento
    INNER JOIN cliente ON agendamento.uniqueIdCliente= cliente.uniqueId)
    INNER JOIN servico ON agendamento.uniqueIdServico = servico.uniqueId)";
    $calendar = array();
    foreach ($conn->query($sql) as $row) {
        $arrayItem = array(
            'uniqueId' => $row['uniqueId'],
            'client' => $row['nome'],
            'service' => $row['titulo'],
            'dataHora' => $row['dataHora'],
            'confirm' => $row['confirm'],
            'price' => $row['preco'],
            //            'status' => $row['status']
        );
        array_push($calendar, $arrayItem);
    }
    $res['calendar'] = $calendar;
}

if ($action == "confirm") {
    $res = array();
    $uniqueId = $_POST['uniqueId'];
    $sql = $conn->prepare("UPDATE `agendamento` SET confirm=:confirm WHERE uniqueId=:uniqueId");

    $sql->execute(
        array(
            ':confirm' => 1,
            ':uniqueId' => $uniqueId,
        )
    );

    $result = $sql->rowCount();
    if ($result > 0) {
        $res['error'] = false;
        $res['message'] = "Usuário atualizado com sucesso!";
        $res['confirm'] = 1;
    } else {
        $res['error'] = true;
        $res['message'] = "Não foi possível atualizar o cliente. Tente novamente!";
    }
}

if ($action == "updateDrop") {
    $res = array();
    $uniqueId = $_POST['uniqueId'];
    $dataHora = $_POST['dataHora'];
    $sql = $conn->prepare("UPDATE `agendamento` SET confirm=:confirm, 
    dataHora=:dataHora WHERE uniqueId=:uniqueId");

    $sql->execute(
        array(
            ':confirm' => 0,
            ':uniqueId' => $uniqueId,
            ':dataHora' => date('Y-m-d H:i:s', strtotime($dataHora))
        )
    );

    $result = $sql->rowCount();
    if ($result > 0) {
        $res['error'] = false;
        $res['message'] = "Usuário atualizado com sucesso!";
        $res['confirm'] = 1;
    } else {
        $res['error'] = true;
        $res['message'] = "Não foi possível atualizar o cliente. Tente novamente!";
    }
}

if ($action == "addNewItem") {
    $res = array();
    $uniqueIdCliente = $_POST['idCliente'];
    $uniqueIdServico = $_POST['idServico'];
    $dataHora = $_POST['dataHora'];
    $uniqueId = getUnique($uniqueIdCliente);
    ;

    $sql = $conn->prepare("INSERT INTO `agendamento`(confirm, uniqueId, uniqueIdCliente, uniqueIdServico, dataHora) VALUES (:confirm, :uniqueId, :uniqueIdCliente, :uniqueIdServico, :dataHora)");

    $sql->execute(
        array(
            ':confirm' => 0,
            ':uniqueId' => $uniqueId,
            ':uniqueIdCliente' => $uniqueIdCliente,
            ':uniqueIdServico' => $uniqueIdServico,
            ':dataHora' => date('Y-m-d H:i:s', strtotime($dataHora))
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

if ($action == "deleteItem") {
    $res = array();
    $uniqueId = $_POST['uniqueId'];
   // $conn->prepare("DELETE FROM agendamento WHERE uniqueId=?")->execute([$uniqueId]);
    $sql = "DELETE FROM agendamento WHERE uniqueId=?";
    $statement = $conn->prepare($sql);
    if($statement->execute([$uniqueId])) {
        echo "Post deleted successfully!";
      }
}

if ($action == 'getClientById') {
    $res = array();
    $uniqueId = $_POST['uniqueId'];
    $uniqueIdClient = $_POST['uniqueIdClient'];
    $sql = "SELECT agendamento.uniqueId, agendamento.dataHora, cliente.nome, servico.titulo, servico.preco
    FROM ((agendamento
    INNER JOIN cliente ON agendamento.uniqueIdCliente= cliente.uniqueId)
    INNER JOIN servico ON agendamento.uniqueIdServico = servico.uniqueId) WHERE agendamento.uniqueId='$uniqueId'";
    $agendamento = array();
    foreach ($conn->query($sql) as $row) {
        $arrayItem = array(
            'uniqueId' => $row['uniqueId'],
            'dataHora' => $row['dataHora'],
            'client' => $row['nome'],
            'title' => $row['titulo'],
            'price' => $row['preco'],
        );
        array_push($agendamento, $arrayItem);
        $res['agendamento'] = $agendamento;
    }
}

if ($action == "getClientByMatricula") {
    $res = array();
    $matricula = $_POST['matricula'];
    $sql = "SELECT noContribuinte FROM `agendamento` WHERE matricula='$matricula'";
    $noContribuinte = array();
    foreach ($conn->query($sql) as $row) {
        $arrayItem = array(
            'noContribuinte' => $row['noContribuinte'],
        );
        array_push($noContribuinte, $arrayItem);
        $res['email'] = getClientEmailByMatricula($conn, $noContribuinte);
    }
}

function getClientEmailByMatricula($conn, $noContribuinte)
{
    $cont = $noContribuinte[0]['noContribuinte'];
    $sql = "SELECT email FROM `clientes` WHERE noContribuinte='$cont'";
    $email = array();
    foreach ($conn->query($sql) as $row) {
        $arrayItem = array(
            'email' => $row['email'],
        );
        array_push($email, $arrayItem);
        return $email;
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