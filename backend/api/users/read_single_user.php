<?php 
//headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

//initializing our api
include_once('../../core/initialize.php');

//instantiate post
$post = new User($db);

$post->uniqueId = isset($_GET['id']) ? $_GET['id'] : die();
$post->read_single();

$post_arr = array(
    'uniqueId'=> $post->uniqueId, 
    'username'=> $post->username, 
    'name'=> $post->name, 
    'email'=> $post->email, 
    'adm'=> $post->adm, 
);

//make a JSON
print_r(json_encode($post_arr));
?>