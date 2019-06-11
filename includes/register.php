<?php
include('./includes/sendMail.php');

$registerView = './views/register.html';
$successView = './views/reg_success.html';
$regConfirmView = './views/regConfirm.html';

$zip = inputValiScrub($_POST['zip'],'number');
$email = inputValiScrub($_POST['email'],'email');
if(!$email){
    $error = 'Invalid email address';
    include($registerView);
}
$query = 'SELECT user_email from users where user_email=?';
$statement = $conn->prepare($query);
$statement->execute([$email]);
$emailMatches = $statement->rowCount();
if($emailMatches!==0){
    $error = "There's already an account using that email address.";
    include($registerView);
} else {
    $regString = randomRegGen();
    $insertQuery = 'INSERT INTO users (user_email, user_key, user_zip, user_regString, user_regStringExp) VALUES (:email, :password, :zip, :string, NOW() + INTERVAL 48 HOUR)';
    $insStmt = $conn->prepare($insertQuery);
    $insStmt->execute(['email'=>$email,'zip'=>$zip,'password'=>password_hash($_POST['password'],PASSWORD_DEFAULT), 'string'=>$regString]);
    $logEmailInfo = ['email'=>$email, 'topic'=>'reg', 'userID'=>'', 'regString'=>$regString, 'siteRoot'=>$siteRoot];
    sendTheEmail($logEmailInfo);
    logEmail($logEmailInfo);
    include($regConfirmView);
}