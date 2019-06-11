<?php

$email = inputValiScrub($_POST['email'],'email');
$hashedPW = password_hash($_POST['password'], PASSWORD_DEFAULT);

$resetQuery = 'UPDATE users SET user_key=? WHERE user_email = ?';
$resetStmt = $conn->prepare($resetQuery);
$resetStmt->execute([$hashedPW, $email]);
$resetResult = $resetStmt->rowCount();

if($resetResult==1){
    $clearResetCodeQ = 'UPDATE users SET user_resetString = ?, user_resetStringExp = ?';
    $clearResetStmt = $conn->prepare($clearResetCodeQ);
    $clearResetStmt->execute([null,0]);
    //go to success page
    include('./views/reset_success.html');
} else {
    //go to fail page
    include('./views/reset_fail.html');
}