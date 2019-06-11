<?php

if(!isset($_SESSION['userID'])){
    include('./includes/signin.php');
}
?>
<main id="profile">
    
    <h2>Profile</h2>
    <a href="?page=settings" role="button">Settings</a>
    <a href="?page=favorites" role="button">My Favorites</a>
    <a href="?page=updates" role="button">My Updates</a>
</main>