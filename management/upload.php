<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    $targetDirectory = "../assets/images/";

    $filename = $_FILES['file']["name"];
    $tempFilePath = $_FILES['file']["tmp_name"];

    if (move_uploaded_file($tempFilePath, $targetDirectory . $filename)) {
        $fileUrl = 'url("./images/' . $filename . '")';
        echo json_encode(['success' => true, 'fileUrl' => $fileUrl]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error uploading file.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
}
?>
