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

//    $fileName = $_FILES["file"]["name"];
//    $destination = __DIR__ . "/uploads/$fileName";
//    // to move the uploaded file from the temporary folder to a folder we chose
//    $i=1;
//    while (file_exists($destination)) {
//        $fileName = "($i)".$fileName;
//        $i++;
//    }
//
//    if ( ! move_uploaded_file($_FILES["file"]["tmp_name"], $destination)){
//        exit("can't move uploaded file !");
//    }


//    $EMAILS_FILE = "Emails.txt";
    $DIRECTORY = __DIR__ . "/uploads/" ;
    $VALIDATE_EMAIL_FILE_NAME = $DIRECTORY . "EmailsV.txt";
    $SORTED_VALIDATE_EMAIL_FILE_NAME = $DIRECTORY . "EmailsT.txt";
    $NON_VALIDATE_EMAIL_FILE_NAME = $DIRECTORY . "AdressesNonValides.txt";
    $EMAIL_PATTERN = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

    if(!($file = fopen($_FILES["file"]["tmp_name"], "r"))){
        echo "Unable to open file!" ;
        exit(1);
    }

    $validateEmails = [];
    $nonValidEmails = [];
    $domainEmails = [];

    while (!feof($file)){
        $tmpEmail = trim(fgets($file));

        if (preg_match($EMAIL_PATTERN, $tmpEmail)){
            if (!in_array($tmpEmail, $validateEmails)){
                $domainName = substr($tmpEmail , strpos($tmpEmail,"@")+1,strlen($tmpEmail));
                $validateEmails[] = $tmpEmail ;
                $domainEmails[$domainName][]= $tmpEmail;
            }
        }else {
            if (!in_array($tmpEmail, $nonValidEmails)){
                $nonValidEmails[]= $tmpEmail ;
            }
        }
    }
    fclose($file);

    writeEmailsToFile($VALIDATE_EMAIL_FILE_NAME, $validateEmails);
    writeEmailsToFile($NON_VALIDATE_EMAIL_FILE_NAME, $nonValidEmails);

    sort($validateEmails);
    writeEmailsToFile($SORTED_VALIDATE_EMAIL_FILE_NAME, $validateEmails);

    foreach( $domainEmails as $domainName => $emails){
        writeEmailsToFile($DIRECTORY . $domainName.".txt", $emails);
    }

    // functions :
    function writeEmailsToFile( $fileName , $emails){
        $file = fopen($fileName , "w");
        foreach ( $emails as $email){
            fwrite($file , $email . "\n");
        }
        fclose($file);
    }
    header("Location: Page2.html");