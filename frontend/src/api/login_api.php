<?php
include 'db_connection.php';
$conn = OpenCon();
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
//User login and password
if ($action == "login") {
    $res = array();
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * from `users` WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    $num = mysqli_num_rows($result);
    if ($num > 0) {
        $res['message'] = "Login Successfully";
        $res['error'] = false;
    } else {
        $res['error'] = true;
        $res['message'] = "Your Login Email or Password is invalid";
    }
}

//User Register new user and password
if ($action == "register") {
    $res = array();
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $terms = $_POST['terms'];
    $uniqueId = getUnique($name);
    $adm = 0;

    $result = '';
    if (!isEmailRegistered($conn, $email)) {
        $sql = "INSERT INTO `users` (uniqueId, name, username, email, password, adm, terms) VALUES ('$uniqueId', '$name','$email','$email','$password','$adm', '$terms')";
        $result = $conn->query($sql);

    }

    if ($result > 0) {
        $res['error'] = false;
        $res['message'] = "Usuário atualizado com sucesso!";
    } else {
        $res['error'] = true;
        $res['message'] = "Não foi possível atualizar o cliente. Tente novamente!";
    }
}

function isEmailRegistered($conn, $email)
{
    $sql = "SELECT email FROM users WHERE email='$email'";

    $result = $conn->query($sql);
    $num = mysqli_num_rows($result);
    if ($num > 0) {
        return true;
    } else {
        return false;
    }
}

//Get Current User Info
if ($action == "getCurrentUser") {
    $res = array();
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * from `users` WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    $num = mysqli_num_rows($result);
    $currentUser = array();
    if ($num > 0) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($rows as $row) {
            $currentUser = array(
                'userName' => $row["username"],
                'name' => $row["name"],
                'email' => $row["email"],
                'adm' => $row['adm'],
                'uniqueId' => $row['uniqueId']
            );

            $res['currentUser'] = $currentUser;
        }
        $res['error'] = false;
    } else {
        $res['error'] = true;
    }
}

//>>>>>>>>>>>>>>>>>>>>>>>>>
function getUnique($userName)
{
    $ini = substr($userName, 0, 3);
    $now = DateTime::createFromFormat('U.u', microtime(true));

    $string = $ini . $now->format("YmdHis.u");

    return $string;
}

CloseCon($conn);
header("Content-type: application/json");
echo json_encode($res);
die();