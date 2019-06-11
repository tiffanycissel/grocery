<?php

$signInView = './views/signin.html';
$welcomeView = './views/welcome.html';
$updateView = './views/updateItem.html';
$homeView = './views/home.html';

//clear expired reg codes
$codeClearQuery = 'UPDATE users SET user_regString=null WHERE user_email=? AND user_regStringExp < NOW()';
$codeClearStmt = $conn->prepare($codeClearQuery);
$codeClearStmt->execute([$_POST['email']]);

if(isset($_POST['conf'])){
    if(md5($_POST['email'])!=$_POST['id']){
        //invalid email error
        include('./views/reg_fail.html');
    }
    $confQuery = 'SELECT user_regString FROM users WHERE user_email=?';
    $confStmt = $conn->prepare($confQuery);
    $confStmt->execute([$_POST['email']]);
    $confResult = $confStmt->fetch();
    if($confResult['user_regString']!='1'){
        if($confResult['user_regString']!=$_POST['conf']){
            if($confResult['user_regString']==null){
                //link expired; offer to send new link
                include('./views/reg_fail.html');
            } else {
                //conf mismatch; invalid attempt
                include('./views/reg_fail.html');
            }
        } else {
            $updateConfQuery = 'UPDATE users SET user_regString=?, user_regStringExp=? WHERE user_email = ?';
            $updateConfStmt = $conn->prepare($updateConfQuery);
            $updateConfStmt->execute(['1',null,$_POST['email']]);
        }
    }    
}

$email = inputValiScrub($_POST['email'],'email');
if(!$email){
    $error = 'Invalid email address';
    include($signInView);
}
$query = 'SELECT user_email from users where user_email=?';
$statement = $conn->prepare($query);
$statement->execute([$email]);
$emailMatches = $statement->rowCount();
if($emailMatches===0){
    $error = "That email isn't registered.".' <a href="?page=register">Register for an account</a>';
    include($signInView);
} else {
    $userQuery = 'SELECT user_id, user_key, user_zip, user_radius, user_regString from users WHERE user_email=?';
    $userStatement = $conn->prepare($userQuery);
    $userStatement->execute([$email]);
    $userInfo = $userStatement->fetch();
    $hashedPw = $userInfo['user_key'];

    if($userInfo['user_regString']!='1'){
        if($userInfo['user_regString']==null){
            //conf link expired; offer to send new link?
            include('./views/reg_fail.html');
        } else {
            //user not yet confirmed; prompt to check email/complete registration process
            include('./views/regConfirm.html'); 
        }
    }

    if(password_verify($_POST['password'],$hashedPw)){
        include('./includes/zipDistFunctions.php');
        $_SESSION['userEmail'] = $email;
        $_SESSION['userID'] = $userInfo['user_id'];
        $_SESSION['userZip'] = $userInfo['user_zip'];
        $_SESSION['userRadius'] = $userInfo['user_radius'];
        $_SESSION['fiveMileZips'] = getNearbyZips($_SESSION['userZip'],5);
        $_SESSION['tenMileZips'] = getNearbyZips($_SESSION['userZip'],10);
        $_SESSION['fifteenMileZips'] = getNearbyZips($_SESSION['userZip'],15);
        $_SESSION['twentyMileZips'] = getNearbyZips($_SESSION['userZip'],20);
        $_SESSION['twentyfiveMileZips'] = getNearbyZips($_SESSION['userZip'],25);
        $_SESSION['stateZips'] = getStateZips($_SESSION['userZip']);
        if($_SESSION['userRadius']===15 || !isset($_SESSION['userRadius'])){
            $_SESSION['radius'] = $_SESSION['fifteenMileZips'];
        } elseif($_SESSION['userRadius']===5){
            $_SESSION['radius'] = $_SESSION['fiveMileZips'];
        } elseif($_SESSION['userRadius']===10){
            $_SESSION['radius'] = $_SESSION['tenMileZips'];
        } elseif($_SESSION['userRadius']===20){
            $_SESSION['radius'] = $_SESSION['twentyMileZips'];
        } elseif($_SESSION['userRadius']===25){
            $_SESSION['radius'] = $_SESSION['twentyfiveMileZips'];
        } else{
            $_SESSION['radius'] = $_SESSION['stateZips'];
        }
        // include($welcomeView/$homeView);
        if(isset($_SESSION['holdUpdateVals']) && isset($_SESSION['tempUpdateVals'])){
            include($updateView);
        } else {
            // include($welcomeView);
            header('Location: ?page=home');
        }
    } else {
        $error = 'Wrong password. Try again.';
        include($signInView);
    }
}