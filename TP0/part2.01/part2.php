<?php
    use PHPMailer\PHPMailer\PHPMailer;

    require '../PHPMailer/vendor/autoload.php';

    $fileUploaded =false ;
    $sendMail =false;

    global $domainEmails ;
    global $validateEmails ;
    global $mail ;

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
        }elseif(isset($_POST["sendMail"])){
            $sendMail = true;
        }elseif(isset($_POST["sendEmailToAll"])){

            $subject = $_POST["subject"];
            $body = $_POST["messageBody"];

            sendEmailToAll($subject, $body);
        }
    }


?>

<!DOCTYPE html>
<html >
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    </head>

    <body>
            <?php
                if (!$fileUploaded && !$sendMail) {
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
                if ($fileUploaded && !$sendMail) {
                    echo '<h3>Send a message to valid emails by completing the formulaire  : </h3>';
            ?>
            <form method="post">
                <button name = "sendMail"> Show formulaire </button><br>
            </form>

            <?php
                    afficheDownloadeButton("The file contain the valid e`mails : ","uploads\EmailsV.txt");
                    afficheDownloadeButton("The file contain the non valid emails : ","uploads\AdressesNonValides.txt");
                    afficheDownloadeButton("The file contain the valid sorted emails : ","uploads\EmailsT.txt");
                    echo '<h3>    --- THE DOMAINES ---    </h3>';
                        $key = array_keys($domainEmails);
                        foreach ($key as $domaine){
                            afficheDownloadeButton("The file contain the domaine {$domaine}","uploads/Domaine_Emails/{$domaine}.txt");
                        }
                }
                if($sendMail){
            ?>
                    <form method="post">
                        <h3>Send email message to the valid email in the text file </h3>
<!--                        <label>The email of the sender : </label>-->
<!--                        <input type="email" required="required" placeholder="example@gmail.com" name="senderEmail"><br>-->
                        <label>The message subject : </label>
                        <input required="required"  name="subject"><br>
                        <label>The message body :</label>
                        <textarea  required name="messageBody"></textarea><br>
                        <button name = 'sendEmailToAll'>Send</button>
                    </form>

            <?php
                }
            ?>

    </body>
</html>

<?php
// functions
    function createFiles(){

        global $domainEmails ;
        global $validateEmails ;

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
        echo '<button><a href = "'.$pathFile.'" download > Download </a></button><br> ';
    }

    function sendEmail($receiver,$subject,$message){
        global $mail;
        try {
            $mail->addAddress($receiver);
            $mail->Subject = $subject ;
            $mail->Body    = $message ;
            $mail->send();
            $mail->clearAddresses();
        }catch (Exception $e){
            $mail->clearAddresses();
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    function sendEmailToAll($subject,$message){
        setUpPHPMailer();
        $file = fopen(__DIR__.'/uploads/EmailsT.txt',"r");
        while (!feof($file)){
            $email=trim(fgets($file));
            sendEmail($email,$subject,$message);
        }
        fclose($file);
    }
    function setUpPHPMailer(){
        global $mail;
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'hkkh2655@gmail.com';
        $mail->Password = 'vmsadktpqfvatgeu';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $mail->setFrom("hkkh2655@gmail.com");
    }
?>