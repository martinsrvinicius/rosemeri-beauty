<?php
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initializing our api
include_once('../../core/initialize.php');

//instantiate user
$user = new User($db);

$action = '';
if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if ($action == 'login') {
    $user->email = $_POST['email'];
    $user->password = $_POST['pass'];
 if ($user->login()) {
    $user_arr = $user->login();
        /*echo json_encode(
            array('message' => 'Login successfully.')
        );*/
    } else {
       /* echo json_encode(
            array('message' => 'Login went wrong.')
        );*/
    }
}
//make a JSON
print_r(json_encode($user_arr));
?>