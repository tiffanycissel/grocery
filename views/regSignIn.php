<?php
$emailHash = $_GET['id'];
$confCode = $_GET['conf'];
?>
<main id="regSignIn">
    
    <h2>Sign In</h2>
    <p>Sign-in to complete your registration</p>
    <form method="POST" action="index.php">
        <input type="hidden" name="task" value="signin">
        <input type="email" placeholder="eMail Address" name="email" id="emailInput">
        <input type="password" placeholder="Password" name="password" id="pwInput">
        <input type="hidden" name="id" value="<?php echo $emailHash; ?>">
        <input type="hidden" name="conf" value="<?php echo $confCode; ?>">
        <input type="submit" value="Sign In">        
    </form>
</main>