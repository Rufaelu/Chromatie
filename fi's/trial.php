<?php
//
//
//use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\Exception;
//
//require '../vendor/autoload.php';
//
//
///**
// * @throws Exception
// */
//function sendEmail($email, $code){
//
//    $mail = new PHPMailer();
//    $mail->isSMTP();
//    $mail->SMTPSecure = 'ssl';
//    $mail->SMTPAuth = true;
//    $mail->Host = 'smtp.gmail.com';
//    $mail->Port = 465;
//    $mail->Username = 'niamraf12@gmail.com';
//    $mail->Password = 'ckfy xmrk pnnc ptuh';
//    $mail->setFrom('niamraf12@gmail.com','Chromatie');
//    $mail->addAddress($email);
//    $mail->Subject = ' Password Reset ';
//    $mail->Body = "Your Password Reset code is $code";
////send the message, check for errors
//    if (!$mail->send()) {
//        echo "ERROR: " . $mail->ErrorInfo;
//    } else {
//        echo "SUCCESS";
//    }
//}
//
//sendEmail('fikirbisrat0@gmail.com',9)
//
//?>