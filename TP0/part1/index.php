<?php
    $EMAILS_FILE = "Emails.txt";
    $VALIDATE_EMAIL_FILE_NAME = "EmailsV.txt";
    $SORTED_VALIDATE_EMAIL_FILE_NAME = "EmailsT.txt";
    $NON_VALIDATE_EMAIL_FILE_NAME = "AdressesNonValides.txt";

    if(!($file = fopen($EMAILS_FILE, "r+"))){
        echo "Unable to open file!" ;
        exit(1);
    }

    $EMAIL_PATTERN = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
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
        writeEmailsToFile($domainName.".txt", $emails);
    }

// functions :
    function writeEmailsToFile( $fileName , $emails){
        $file = fopen($fileName , "w");
        foreach ( $emails as $email){
            fwrite($file , $email . "\n");
        }
        fclose($file);
    }
?>