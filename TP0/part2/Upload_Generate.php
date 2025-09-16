<?php
// to check if the user uploaded a file
    if (empty($_FILES)){
        exit('$_FILES is empty !!');
    }
// to test if the upload has done successfully
    if($_FILES["file"]["error"] != UPLOAD_ERR_OK ){
        // better to use a switch case to affiche a personalise message for every error
         exit('$_FILES error !!');
    }

    $fileName = $_FILES["file"]["name"];
    $destination = __DIR__ . "/uploads/Emails.txt";
    // to move the uploaded file from the temporary folder to a folder we chose
//    $i=1;
//    while (file_exists($destination)) {
//        $fileName = "($i)".$fileName;
//        $i++;
//    }
//
    if ( ! move_uploaded_file($_FILES["file"]["tmp_name"], $destination)){
        exit("can't move uploaded file !");
    }
    header("Location: Page2.html");
