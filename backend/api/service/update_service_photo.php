<?php
//headers
header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');
header('Content-Type: multipart/form-data');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access Control-Allow-Methods, Authorization, X-Requested-With');


//initializing our api
include_once('../../core/initialize.php');

//instantiate service
$service = new Service($db);

//get raw posted data
if (count($_FILES) > 0) {
    if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
        $type = $_FILES['foto']['type'];
        $imgData = file_get_contents($_FILES['foto']['tmp_name']);
        $imageProperties = getimageSize($_FILES['foto']['tmp_name']);
        $service->foto = $imgData;
    }
}

$service->uniqueId = $_POST['uniqueId'];

//Update service info
if($service->update_photo()) {
    echo json_encode(array($res['status'] = true));
} else {
    echo json_encode(array($res['status'] = false));
}