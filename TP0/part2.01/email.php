<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../PHPMailer/vendor/autoload.php';

    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'hkkh2655@gmail.com';                     //SMTP username
        $mail->Password   = 'vmsadktpqfvatgeu';                               //SMTP password
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('hkkh2655@gmail.com');
        $mail->addAddress('aminebiyadi4@gmail.com');     //Add a recipient
//        $mail->addAddress('ellen@example.com');               //Name is optional
//        $mail->addReplyTo('info@example.com', 'Information');
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');

        //Attachments
//        $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
//        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body';
//        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }