<?php
include_once 'set.php';
$act = isset($_POST['act']) ? $_POST["act"] : null;
if(_m5($act,"video")) {
    $id = $_POST['id'];
    $thumbnailData = $_POST['data'];
    $thumbnailPath = $_POST['path'];
    $thumbnailData = str_replace('data:image/png;base64,', '', $thumbnailData);
    $thumbnailData = str_replace(' ', '+', $thumbnailData);
    $thumbnailBinary = base64_decode($thumbnailData);
    file_put_contents("uploads/thumb/".$thumbnailPath, $thumbnailBinary);
    $update = _query("UPDATE file SET _thumb='/uploads/thumb/$thumbnailPath' WHERE id='$id'");
    if(!$update){
        echo "Lỗi upldate cmnr". "UPDATE file SET _thumb='/uploads/thumb/$thumbnailPath' WHERE id='$id'";
    } else {
        echo $thumbnailPath;
    }

} else if(_m5($act,"image")) {
    $id = $_POST['id'];
    $sourceImagePath = $_POST['data'];
    $destinationImagePath = $_POST['path'];
    $desiredFileSize = 100 * 1024;
    $compressionQuality = 80;
    $imageData = file_get_contents(_domain_.$sourceImagePath);
    do {
        $compressedImageData = compressImage($imageData, $compressionQuality);
        $compressedFileSize = strlen($compressedImageData);
        if ($compressedFileSize > $desiredFileSize) {
            $compressionQuality -= 5;
        }


    } while ($compressedFileSize > $desiredFileSize && $compressionQuality > 0);
    file_put_contents("uploads/thumb/".$destinationImagePath, $compressedImageData);

    $update = _query("UPDATE file SET _thumb='/uploads/thumb/$destinationImagePath' WHERE id='$id'");
    if(!$update){
        echo "Lỗi upldate cmnr". "UPDATE file SET _thumb='uploads/thumb/$destinationImagePath' WHERE id='$id'";
    } else {
        echo $thumbnailPath;
    }



}
?>