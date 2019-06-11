<?php

if(!isset($_SESSION['userID'])){
    include('./includes/signin.php');
}

$faveQuery = 'SELECT * from favorites where user_id = ?';
$stmt = $conn->prepare($faveQuery);
$stmt->execute([$_SESSION['userID']]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$catIDs = [];
$resultsByCatID = [];
foreach($results as $key=>$val){
    $catID = getCatFromSubcat($val['subcategory_id']);
    if(!in_array($catID,$catIDs)){
        array_push($catIDs,$catID);
    }
}
sort($catIDs);
foreach($catIDs as $ID){
    $resultsByCatID[$ID] = [];
}
$resultListOutput = [];
foreach($results as $key=>$val){
    $itemLink =  '<li><a href="#" data-fave-id="'.$val['favorite_id'].'"';
    $itemText = '';
    if($val['brand_id']!=0){        
        $itemText = getBrandName($val['brand_id']);
        if($val['subbrand_id']!=0){
            $itemText = $itemText.', '.getSubbrandName($val['subbrand_id']).' ';
            $itemLink = $itemLink.'data-match-id="'.$val['subbrand_id'].'" data-match-type="sb" data-subcat-id="'.$val['subcategory_id'].'" data-brand-id="'.$val['brand_id'].'"';
        } else {
            $itemLink = $itemLink.'data-match-id="'.$val['brand_id'].'" data-match-type="b" data-subcat-id="'.$val['subcategory_id'].'"';
            $itemText = $itemText.' ';
        }
    } else {
        $itemLink = $itemLink.'data-match-id="'.$val['subcategory_id'].'" data-match-type="sc/'.getCatFromSubcat($val['subcategory_id']).'"';        
    }
    $itemText = $itemText.getSubcatName($val['subcategory_id']);
    if($val['item_organic']==1){
        $itemText = $itemText.', organic';
        $itemLink = $itemLink.' data-organic="1">';
    } else {
        $itemLink = $itemLink.' data-organic="0">';
    }
    if($val['brand_id']==0){
        $itemText = $itemText.' (all brands)';
    }
    $item = $itemLink.$itemText.'</a></li>';
    array_push($resultListOutput,$item);
    array_push($resultsByCatID[getCatFromSubcat($val['subcategory_id'])],$item);
}
?>
<main id="favorites">
    
    <h2>Favorites</h2>
    <form method="post" action="index.php" id="viewFave">
    <input type="hidden" name="faveMatch" value="true">    
    <input type="hidden" name="formType" value="search">
    <input type="hidden" name="task">
    <input type="hidden" name="matchID" id="matchID">
    <input type="hidden" name="matchType" id="matchType">    
    <input type="hidden" id="organic" name="organic">
    <input type="hidden" name="searchTerm" id="search-term-input"> 
    <input type="hidden" name="subcategory_id">
    <input type="hidden" name="brand_id">
    <input type="hidden" name="subbrand_id">   
    </form>
    <div id="faveLinks">
    <?php
    foreach($resultsByCatID as $cat=>$matches){
        echo '<h3>'.getCategoryName($cat).'</h3>';
        echo '<ul>';
        foreach($matches as $listItem){
            echo $listItem;
        }
        echo '</ul>';
    }
    ?>
    </div>
    <form method="POST" action="index.php">
            <input type="hidden" name="task" value="profile">
            <input type="submit" name="profile" value="Back to Profile">
        </form>
</main>