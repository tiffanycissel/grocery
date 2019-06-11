<?php

include('includes/sendMail.php');

$emailAdd = inputValiScrub($_POST['email'],'email');
$emailHash = md5($emailAdd);
$confString = randomRegGen();
$mailInfoVals = ['email'=>$emailAdd, 'topic'=>'reset', 'resetString'=>$confString, 'hashedEmail'=>md5($emailAdd), 'siteRoot'=>$siteRoot];

$resetConfRecordQuery = 'UPDATE users SET user_resetString=?, user_resetStringExp = NOW() + INTERVAL 48 HOUR WHERE user_email=?';
$resetConfRecordStmt = $conn->prepare($resetConfRecordQuery);
$resetConfRecordStmt->execute([$confString,$emailAdd]);

sendTheEmail($mailInfoVals);
?>