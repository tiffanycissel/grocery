<?php

$_SESSION['tempUpdateVals'] = [
'subCat' => $_POST['subCat'],
'subCatName' => $_POST['subCatName'],
'unit' => $_POST['unit'],
'catName' => $_POST['catName'],
'catID' => $_POST['catID'],
'$organic' => $_POST['organic'],
'$type' => $_POST['updateType'],
];
if(isset($_POST['subbrandID'])){
    $_SESSION['tempUpdateVals']['subbrandID'] = $_POST['subbrandID'];
}

$signInView = './views/signin.html';
$updateView = './views/updateItem.html';

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
    $userQuery = 'SELECT user_id, user_key, user_zip, user_radius from users WHERE user_email=?';
    $userStatement = $conn->prepare($userQuery);
    $userStatement->execute([$email]);
    $userInfo = $userStatement->fetch();
    $hashedPw = $userInfo['user_key'];

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
        include($updateView);
    } else {
        $error = 'Wrong password. Try again.';
        include($signInView);
    }
}