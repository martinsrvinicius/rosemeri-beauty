<?php
//headers
header('Access-Control-Allow-Origin: *');
//header('Content-Type: application/json');
header('Content-Type: multipart/form-data');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access Control-Allow-Methods, Authorization, X-Requested-With');


//initializing our api
include_once('../../core/initialize.php');
include_once('../utils/compress_image.php');
include_once('../utils/convert_filesize.php');

//instantiate service
$service = new Service($db);

//File upload path
$uploadPath = "uploads/";

//get raw posted data
if (count($_FILES) > 0) {
    if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
        //OLD
        $type = $_FILES['foto']['type'];
        $imgData = file_get_contents($_FILES['foto']['tmp_name']);
        $imageProperties = getimageSize($_FILES['foto']['tmp_name']);
        //$service->foto = $imgData;

        // File info 
        // $fileName = basename($_FILES['foto']['tmp_name']);
        $fileName = basename($_FILES['foto']['tmp_name']);
        $imageUploadPath = $uploadPath . $fileName;
        $fileType = pathinfo($imageUploadPath, PATHINFO_EXTENSION);
        // Compress size and upload image 
        $imageTemp = $_FILES['foto']['tmp_name'];
        $imageSize = convert_filesize($_FILES['foto']['size']);
        $compressedImage = compressImage($imageTemp, $imageUploadPath, 60);
        $service->foto = $compressedImage;
        //echo 'imgSize:'. $imageSize;

        if ($compressedImage) {
            $compressedImageSize = filesize($compressedImage);
            //echo 'comp: '. $compressedImageSize;

            $compressedImageSize = convert_filesize($compressedImageSize);

            $status = 'success';
            $statusMsg = "Image compressed successfully.";
        } else {
            $statusMsg = "Image compress failed!";
        }
    }
}

$service->uniqueId = $_POST['uniqueId'];

//Update service info
if($service->update_photo()) {
    echo json_encode(array($res['status'] = true));
} else {
    echo json_encode(array($res['status'] = false));
}