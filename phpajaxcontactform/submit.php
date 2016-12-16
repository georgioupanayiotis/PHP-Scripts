<?php
/**
 * Created by PhpStorm.
 * User: panayiotisgeorgiou
 * Date: 16/12/16
 */

session_start();
if($_POST)
{
    require_once 'configuration.php';
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        $output = json_encode(array( //create JSON data
            'type'=>'error',
            'text' => 'Sorry Request must be Ajax POST'
        ));
        die($output); //exit script outputting json data
    }

    //Sanitize input data using PHP filter_var().
    $user_name		= filter_var($_POST["user_name"], FILTER_SANITIZE_STRING);
    $user_email		= filter_var($_POST["user_email"], FILTER_SANITIZE_EMAIL);
    $phone_number	= filter_var($_POST["phone_number"], FILTER_SANITIZE_NUMBER_INT);
    $subject		= filter_var($_POST["subject"], FILTER_SANITIZE_STRING);
    $message		= filter_var($_POST["message"], FILTER_SANITIZE_STRING);
    $captcha_answer	= filter_var($_POST["captcha_answer"], FILTER_SANITIZE_STRING);

    //additional php validation
    if(strlen($user_name) < _USER_NAME_LENGTH ){ // If length is less than 4 it will output JSON error.
        $output = json_encode(array('type'=>'error', 'text' => 'Name is too short or empty!'));
        die($output);
    }
    if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){ //email validation
        $output = json_encode(array('type'=>'error', 'text' => 'Please enter a valid email!'));
        die($output);
    }
    if(!filter_var($phone_number, FILTER_SANITIZE_NUMBER_FLOAT)){ //check for valid numbers in phone number field
        $output = json_encode(array('type'=>'error', 'text' => 'Enter only digits in phone number'));
        die($output);
    }

    if(strlen($message) < _USER_MESSAGE_LENGTH){ //check emtpy message
        $output = json_encode(array('type'=>'error', 'text' => 'Too short message! Please enter something.'));
        die($output);
    }

    if((int)$captcha_answer != $_SESSION['expect_answer']){ //check captcha
        $output = json_encode(array('type'=>'error', 'text' => 'The captcha code is wrong.'));
        die($output);
    }

    $message_body = "Full Name: $user_name<br />Email: $user_email<br />Phone Number: $phone_number<br />Message: ".nl2br($message);

    if(_IS_SMTP === TRUE){
        $send_mail = send_smtp_email(_TO_EMAIL, $subject, $message_body);
    }else{
        $send_mail = send_normal_email(_TO_EMAIL, $subject, $message_body, $cc="", $bcc="");
    }

    if(!$send_mail)
    {
        //If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
        $output = json_encode(array('type'=>'error', 'text' => 'Could not send mail! Please check your PHP mail configuration.'));
        die($output);
    }else{
        $output = json_encode(array('type'=>'message', 'text' => 'Hi '.$user_name .' Thank you for your email'));
        die($output);
    }
}

function send_smtp_email($toEmail,$subject, $message_body, $cc="", $bcc=""){
    require_once('phpmailer/class.phpmailer.php');// path to the PHPMailer class
    require_once('phpmailer/class.smtp.php');
    $mail = new PHPMailer();
    $mail->CharSet =  "utf-8";
    $mail->IsSMTP();  // telling the class to use SMTP
    $mail->Mailer = "smtp";
    $mail->Host = _SMTP_HOST;
    $mail->Port = _SMTP_PORT;
    $mail->SMTPAuth = true; // turn on SMTP authentication
    $mail->Username = _SMTP_USERNAME; // SMTP username
    $mail->Password = _SMTP_PASSWORD; // SMTP password

    $mail->setFrom($toEmail, _FROM_EMAIL_NAME);
    $mail->AddAddress($toEmail); // name is optional
    //$mail->AddAddress('to_email_1@domain.com'); // for multiple recipient
    if($cc)
        $mail->addCC($cc);

    if($bcc)
        $mail->addBCC($bcc);

    $mail->Subject  =  $subject;
    $mail->IsHTML(true);
    $mail->Body	= $message_body;
    if ($mail->send()) {
        return true;
    } else {
        return false;
    }
}

function send_normal_email($toEmail,$subject, $message_body, $cc="", $bcc=""){
    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    // Additional headers
    $headers .= 'From: '._FROM_EMAIL_NAME.' <'.$toEmail.'>' . "\r\n";
    if($cc)
        $headers .= 'Cc: '.$cc . "\r\n";

    if($bcc)
        $headers .= 'Bcc: '.$bcc . "\r\n";

    $sendmail = mail($toEmail, $subject, $message_body, $headers);
    return $sendmail;
}
?>