<?php

    $DIRECTORY = __DIR__ . "/uploads/" ;
    $VALIDATE_EMAIL_FILE_NAME = $DIRECTORY . "EmailsV.txt";
    $SORTED_VALIDATE_EMAIL_FILE_NAME = $DIRECTORY . "EmailsT.txt";
    $NON_VALIDATE_EMAIL_FILE_NAME = $DIRECTORY . "AdressesNonValides.txt";

    if (isset($_POST['Valid'])) {
        if (file_exists($VALIDATE_EMAIL_FILE_NAME)) {
            header('Content-Disposition: attachment; filename="'."EmailsV.txt".'"');
            readfile($VALIDATE_EMAIL_FILE_NAME);
            exit;
        } else {
            echo "File not found!";
        }
    }

