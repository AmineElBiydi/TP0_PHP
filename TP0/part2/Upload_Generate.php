<?php
    if (empty($_FILES)){
        exit('$_FILES is empty !!');
    }
    if($_FILES["file"]["error"] != UPLOAD_ERR_OK ){
         exit('$_FILES error !!');
    }

    $fileName = $_FILES["file"]["name"];
    $destination = __DIR__ . "/uploads/Emails.txt";

    if ( ! move_uploaded_file($_FILES["file"]["tmp_name"], $destination)){
        exit("can't move uploaded file !");
    }
    header("Location: Page2.html");
