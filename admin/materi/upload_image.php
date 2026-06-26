<?php

if (isset($_FILES['upload'])) {

    $file = $_FILES['upload'];

    $fileName = time() . "_" . $file['name'];

    move_uploaded_file(
        $file['tmp_name'],
        "../../assets/uploads/materi/" . $fileName
    );

    $url = "../../assets/uploads/materi/" . $fileName;

    echo json_encode([
        "uploaded" => 1,
        "fileName" => $fileName,
        "url" => $url
    ]);
}
