<?php
if(empty($_POST['email2'])){
    $emailInput = '<input type="email" name="email" value="">';
} else {
    $emailInput = '<input type="email" name="email" value="'.$_POST['email2'].'">';
}
?>
<main id="forgotPW">

<h2>Forgot Password</h2>
<p>Provide your email below in order to reset your password</p>
<form method="POST" action="index.php">
    <input type="hidden" name="task" value="resetPW">
    <?php echo $emailInput; ?>
    <input type="submit" value="Submit">
</form>
</main>