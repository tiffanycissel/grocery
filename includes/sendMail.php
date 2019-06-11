<?php 
include_once('assets/functConfig.php');
//$mailInfo an array that contains the following keys: email, topic, fdbkSub*, fdbkMsg*, userID*
use PHPMailer\PHPMailer\PHPMailer;

function sendTheEmail($mailInfo){
    if (session_status() == PHP_SESSION_NONE){
        session_start();
    }
        
    //https://github.com/PHPMailer/PHPMailer/blob/master/examples/gmail.phps
        
    require_once('vendor/autoload.php');
    
    $mail = new PHPMailer(true);
    
    try{
        $siteRoot = $mailInfo['siteRoot'];
        $myEmail = 'tiffany.cissel@gmail.com';  
        if($mailInfo['topic']=='reg') {
            $regString = $mailInfo['regString'];
            $link = $siteRoot.'index.php?task=complete-registration&id='.md5($mailInfo['email']).'&conf='.$regString;
            $mail->addAddress($mailInfo['email']);
            $mail->Subject = '[The Grocery App] Confirm Registration';
            $mail->Body = 'Thanks for registering with The Grocery App. Click the link below to complete your registration. <span style="font-style: italic;">Please note this link expires 48 hours from receipt of this email.</span><br><br><a href="'.$link.'">'.$link.'</a>';
            $mail->AltBody = 'Thanks for registering with The Grocery App. Copy & paste the URL below into your browser\'s address bar to complete your registration. Please note this link expires 48 hours from receipt of this email.\n\n<a href="'.$link.'">'.$link.'</a>';
        } else if($mailInfo['topic']=='reset') {
            $siteRoot = $mailInfo['siteRoot'];
            $resetString = $mailInfo['resetString'];
            $link = $siteRoot.'index.php?task=reset&id='.$mailInfo['hashedEmail'].'&conf='.$resetString;
            $mail->addAddress($mailInfo['email']);
            $mail->Subject = '[The Grocery App] Password Reset';
            $mail->Body = 'You are receiving this email because a request was made to reset the password for the account associated with this email address. If you did not make this request, please ignore this email. <span style="font-style: italic;">Please note this link expires 48 hours from receipt of this email.</span><br><br><a href="'.$link.'">'.$link.'</a>';
            $mail->AltBody = 'You are receiving this email because a request was made to reset the password for the account associated with this email address. If you did not make this request, please ignore this email. Please note this link expires 48 hours from receipt of this email.\n\n<a href="'.$link.'">'.$link.'</a>';
        } else if($mailInfo['topic']=='fdbk') {
            $mail->addAddress($mailInfo['email']);
            $mail->Subject = '[The Grocery App] Thanks for Your Feedback';
            $mail->Body = 'We received the following feedback from you. Thanks for reaching out--we\'ll be back in touch ASAP.<br><br>Subject: '.$mailInfo['fdbkSub'].'<br>Message: '.$mailInfo['fdbkMsg'].'<br>Submitted: '.$mailInfo['time'];
            $mail->AltBody = 'We received the following feedback from you. Thanks for reaching out--we\'ll be back in touch ASAP.\n\nSubject: '.$mailInfo['fdbkSub'].'\nMessage: '.$mailInfo['fdbkMsg'].'\nSubmitted: '.$mailInfo['time'];
        } else if($mailInfo['topic']=='fdbkNotice') {
            $mail->addAddress($myEmail);
            $mail->addReplyTo($mailInfo['email']);
            $mail->Subject = '[The Grocery App] User Feedback Submission';
            $mail->Body = 'From: '.$mailInfo['email'].'<br>Subject: '.$mailInfo['fdbkSub'].'<br>Message: '.$mailInfo['fdbkMsg'].'<br>Submitted: '.$mailInfo['time'];
            $mail->AltBody = 'From: '.$mailInfo['email'].'\nSubject: '.$mailInfo['fdbkSub'].'\nMessage: '.$mailInfo['fdbkMsg'].'\nSubmitted: '.$mailInfo['time'];
        }
          
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->Username = $myEmail;
        $mail->Password = 'xuaqxfprccvuxzlc';
        $mail->setFrom($myEmail,'The Grocery App');
        $mail->isHTML(true);
        $mail->send();
        if($mailInfo['topic']=='reg' || $mailInfo['topic']=='reset' || $mailInfo['topic']=='fdbk'){
            logEmail($mailInfo);
        }
        if($mailInfo['topic']=='reg'){
            include('views/regConfirm.html');
        } else if($mailInfo['topic']=='reset'){
            include('views/resetRequestConf.html');
        } else if($mailInfo['topic']=='fdbk'){
            include('views/feedbackRec.html');
        }         
    } catch (Exception $e) {
        $mailErrorMsg = 'There was an error in sending the email: '.$mail->ErrorInfo;
        include('views/emailFailure.html');
    }
}