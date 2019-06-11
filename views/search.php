<?php
$catQuery = 'SELECT * from categories';
$catStmt = $conn->prepare($catQuery);
$catStmt->execute();
$categories = $catStmt->fetchAll();
?>
<main id="search-page">
    
   
    

    <form class="search" id="search-form" method="post" action="index.php" autocomplete="off">

         <h2>Zip Code</h2>
    <?php
    if(!isset($_SESSION['userZip'])){
        echo '<input type="number" placeholder="Zip code..." min="501" max="99929" name="user_zip" id="user-zip">';
    } else {
        echo '<div><input type="number" name="user_zip" id="user-zip" value="'.$_SESSION['userZip'].'" disabled><span id="change-link" aria-hidden="false"><a href="#" id="change-zip">Change Zip</a></span><span id="save-cancel-links" class="hide" aria-hidden="true"><a href="#" id="save-zip">Save</a> | <a href="#" id="cancel-zip">Cancel</a></span></div>';
    }
    ?>
    <h2>Search</h2>
    <input type="text" placeholder="Enter keyword..." name="searchTerm" id="search-term-input">
    <div id="suggestions"></div>
    <input type="hidden" name="formType" value="search">
    <input type="hidden" name="task" value="search-form-sub">
    <input type="hidden" name="matchID" id="matchID">
    <input type="hidden" name="matchType" id="matchType">    
    <div class="orgCheck">
        <input type="checkbox" id="organic" name="organic" value="1">
        <label for="organic">Organic</label>        
    </div>
<input type="submit" value="submit">
    </form>
</main>