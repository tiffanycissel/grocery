<?php

include('includes/zipDistFunctions.php');

if(!isset($_SESSION['userZip'])){
    $_SESSION['userZip'] = $_POST['user_zip'];
    $_SESSION['radius'] = getNearbyZips($_SESSION['userZip'],15);
} else if(isset($_POST['user_zip']) && $_SESSION['userZip']!==$_POST['user_zip']) {
    $_SESSION['userZipTemp'] = $_POST['user_zip'];
    $_SESSION['radius'] = getNearbyZips($_SESSION['userZipTemp'],15);
} else {
    $_SESSION['radius'] = getNearbyZips($_SESSION['userZip'],15);
}


    $term = $_POST['searchTerm'];
    $matchID = $_POST['matchID'];
    if($_POST['matchType']==='b'){
        $matchType = 'brand';
    } else if ($_POST['matchType']==='sb'){
        $matchType = 'subbrand';
    } else {
        $matchType = 'subcategory';
    }
    if(isset($_POST['organic'])){
        $organic = 1;
    } else {
        $organic = 0;
    }


if($matchType==='subcategory'){
    $searchQuery = 'SELECT item_id, i.brand_id, i.subbrand_id, subcategory_id, item_price, item_size, i.store_id,store_zip, item_organic, (item_price/item_size) AS unit_price, brand_name, store_name FROM items i INNER JOIN brands b ON b.brand_id = i.brand_id INNER JOIN stores s ON s.store_id = i.store_id where subcategory_id=? AND item_organic=? AND ('.multiWhere('store_zip',$_SESSION['radius'],'p').') order by item_added DESC';
    $searchStmt = $conn->prepare($searchQuery);
    $executeArray = [$matchID,$organic];
    $executeArray = array_merge($executeArray,$_SESSION['radius']);
    $searchStmt->execute($executeArray);
    $searchResults = $searchStmt->fetchAll(PDO::FETCH_ASSOC);

    $genericMatches = searchQueryOutput($searchResults,'g');
    $regMatches = searchQueryOutput($searchResults,'r');
    $catID = str_replace('sc/','',$_POST['matchType'])+0;
    $catName = getCategoryName($catID);
    $unit = getUnit($matchID);
    $info = ['catID'=>str_replace('sc/','',$_POST['matchType'])+0, 'catName'=>getCategoryName($catID), 'unit'=>getUnit($matchID),'subcatID'=>$matchID];
    $resType = 'unbranded';
    include('views/subcatView.php');
} else if ($matchType==='brand' || $matchType==='subbrand'){
    $searchQuery = 'SELECT * from (SELECT DISTINCT i.subcategory_id, s.subcategory_name, i.item_organic, i.subbrand_id, i.brand_id FROM items i INNER JOIN subcategories s ON s.subcategory_id = i.subcategory_id WHERE i.item_organic=0 AND i.'.$matchType.'_id=? UNION SELECT DISTINCT i.subcategory_id, concat(s.subcategory_name,", organic"), i.item_organic, i.subbrand_id, i.brand_id FROM items i INNER JOIN subcategories s ON s.subcategory_id = i.subcategory_id WHERE i.item_organic=1 AND i.'.$matchType.'_id=?) t order by subcategory_name';
    $searchStmt = $conn->prepare($searchQuery);
    $searchStmt->execute([$matchID,$matchID]);
    $results = $searchStmt->fetchAll(PDO::FETCH_ASSOC);
    $subcatID = $results[0]['subcategory_id'];
    $info = ['id'=>$matchID,'name'=>$term,'catID'=>getCatFromSubcat($subcatID), 'catName'=>getCategoryName(getCatFromSubcat($subcatID)), 'unit'=>getUnit($subcatID),'subcatID'=>$subcatID];
    include('views/brandMatches.php');
}


?>
