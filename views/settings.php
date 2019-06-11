<?php

if(!isset($_SESSION['userID'])){
    include('./includes/signin.php');
}
?>
<main id="settings">
    
    <h2>Settings</h2>
    <ul>
        <li><span class="settingTitle">E-Mail Address</span><span id="emailValue"><?php echo $_SESSION['userEmail']; ?></span><form id="emailUpdateForm" class="hide"><input type="email" name="newEmail" disabled></form><a href="#change" id="emailUpdate">Change</a><div class="hide"><a href="#save">Save</a><a href="#cancel">Cancel</a></div></li>
        <li><span class="settingTitle">Zip Code</span> <span id="zipValue"><?php echo $_SESSION['userZip']; ?></span><form id="zipUpdateForm" class="hide"><input type="number" name="newZip" min="1001" max="99950" disabled></form> <a href="#change" id="zipUpdate">Change</a><div class="hide"><a href="#save">Save</a> <a href="#cancel">Cancel</a></div></li>
        <li><span class="settingTitle">Mile Radius</span><span id="radiusValue"><?php echo $_SESSION['userRadius']; ?></span><form id="radiusUpdateForm" class="hide"><input type="number" name="newRadius" disabled></form><a href="#change" id="radiusUpdate">Change</a><div class="hide"><a href="#save">Save</a> <a href="#cancel">Cancel</a></div></li>
    </ul>
    <form method="POST" action="./index.php">
            <input type="hidden" name="task" value="profile">
            <input type="submit" name="profile" value="Back to Profile">
        </form>
</main>