<?php
    $fileUploaded =false ;
    global $domainEmails ;

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if( isset($_POST["upload"])){
            if (empty($_FILES)) {
                exit('$_FILES is empty !!');
            }
            if ($_FILES["file"]["error"] != UPLOAD_ERR_OK) {
                exit('$_FILES error !!');
            }
            $fileUploaded = true;
            $fileName = $_FILES["file"]["name"];
            $destination = __DIR__ . "/uploads/Emails.txt";
            if (!move_uploaded_file($_FILES["file"]["tmp_name"], $destination)) {
                exit("can't move uploaded file !");
            }
            createFiles();
        }
    }

?>

<!DOCTYPE html>
<html>
    <body>

            <?php
                if (!$fileUploaded) {
            ?>
                    <form method="post" enctype="multipart/form-data">
                        <h3> Upload your file : </h3>
                        <input name="file" type="file" value="Chose a file"> <br><br>
                        <button type="submit" name = "upload" >Upload</button>
                    </form>
            <?php
                }
            ?>

            <?php
                if ($fileUploaded) {
                    afficheDownloadeButton("The file contain the valid emails : ","uploads\EmailsV.txt");
                    afficheDownloadeButton("The file contain the non valid emails : ","uploads\AdressesNonValides.txt");
                    afficheDownloadeButton("The file contain the valid sorted emails : ","uploads\EmailsT.txt");
                    echo '<h3>    --- THE DOMAINES ---    </h3>';
                        $key = array_keys($domainEmails);
                        foreach ($key as $domaine){
                            afficheDownloadeButton("The file contain the domaine {$domaine}","uploads/Domaine_Emails/{$domaine}.txt");
                        }
                }
            ?>

    </body>
</html>

<?php
// functions
    function createFiles(){
        global $domainEmails ;
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
    }
    function writeEmailsToFile( $fileName , $emails){
        $file = fopen($fileName , "w");
        foreach ( $emails as $email){
            fwrite($file , $email . "\n");
        }
        fclose($file);
    }

    function afficheDownloadeButton($text,$pathFile){
        echo '<h3>'.$text.'</h3>';
        echo '<button><a href = "'.$pathFile.'" download > Upload </a></button><br> ';
    }

?>