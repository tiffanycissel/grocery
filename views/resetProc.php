<?php
include('./assets/dbconn.php');

$resetEmailId = $_GET['id'];
$resetConf = $_GET['conf'];

$resetConfQuery = 'SELECT user_email FROM users WHERE user_resetString = ?';
$resetConfStmt = $conn->prepare($resetConfQuery);
$resetConfStmt->execute([$resetConf]);
$resetConfResult = $resetConfStmt->fetch();

if(md5($resetConfResult['user_email'])!=$resetEmailId){
    //error in retrieval or email address input
    echo 'Issue with email match.\nMD5 of DB value: '.md5($resetConfResult['user_email']).'\nGET value: '.$resetEmailId;
}
?>
<main id="resetProc">
    
    <h2>Password Reset</h2>
    <p>Provide your new password in the boxes below</p>
    <form method="POST" action="index.php" name="reg">
        <input type="hidden" name="task" value="resetSub">
        <input type="email" placeholder="E-Mail Address" name="email" value="<?php echo $resetConfResult['user_email']; ?>">
        <input type="password" placeholder="Password" name="password" id="pwInput">
        <label for="password">8+ characters long, at least 1 uppercase letter, number or symbol</label>
        <input type="password" placeholder="Confirm Password" name="confPassword" id="confPwInput" disabled> 
        <input type="submit" value="Reset Password" id="resetSub">
    </form>
</main>