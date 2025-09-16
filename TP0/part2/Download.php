<?php
    //    $EMAILS_FILE = "Emails.txt";
    $DIRECTORY = __DIR__ . "/uploads/" ;
    $VALIDATE_EMAIL_FILE_NAME = $DIRECTORY . "EmailsV.txt";
    $SORTED_VALIDATE_EMAIL_FILE_NAME = $DIRECTORY . "EmailsT.txt";
    $NON_VALIDATE_EMAIL_FILE_NAME = $DIRECTORY . "AdressesNonValides.txt";
    $EMAIL_PATTERN = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";

    if(!($file = fopen($DIRECTORY."Emails.txt", "r"))){
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
        writeEmailsToFile($DIRECTORY .'Domaine_Emails/'. $domainName.".txt", $emails);
    }

    if (isset($_POST['Valid'])) {
        downloadFile($VALIDATE_EMAIL_FILE_NAME,"EmailsV.txt");
    }
    if (isset($_POST['nonValild'])) {
        downloadFile($NON_VALIDATE_EMAIL_FILE_NAME,"AdressesNonValides.txt");
    }
    if (isset($_POST['Trie'])) {
        downloadFile($SORTED_VALIDATE_EMAIL_FILE_NAME,"EmailsT.txt");
    }
    if (isset($_POST['Domaines'])) {
        $keysDomaine = array_keys($domainEmails);

        echo "<h3>Your generated files:</h3>";

        foreach ($keysDomaine as $domaine) {
            $filePath = $DIRECTORY . 'Domaine_Emails/' . $domaine . ".txt";

            if (file_exists($filePath)) {
                echo '<a href="'.$filePath.'" download>Download '.$domaine.'</a><br><br><br>';
            }
        }
    }
    // functions :
    function writeEmailsToFile( $fileName , $emails){
        $file = fopen($fileName , "w");
        foreach ( $emails as $email){
            fwrite($file , $email . "\n");
        }
        fclose($file);
    }
    function downloadFile($filePath, $fileName){
        if (file_exists($filePath)) {
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            readfile($filePath);
            exit;
        } else {
            echo "File {$fileName}not found!";
        }
    }

