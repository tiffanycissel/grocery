<?php

$searchQuery = 'SELECT item_id, i.brand_id, subbrand_id, subcategory_id, item_price, item_size, i.store_id,store_zip, item_organic, (item_price/item_size) AS unit_price, brand_name, store_name FROM items i INNER JOIN brands b ON b.brand_id = i.brand_id INNER JOIN stores s ON s.store_id = i.store_id where subcategory_id=? AND item_organic=? AND ('.multiWhere('store_zip',$_SESSION['radius'],'p').') order by item_added DESC';
$searchStmt = $conn->prepare($searchQuery);
$executeArray = [$_POST['subCat'],$_POST['organic']];
$executeArray = array_merge($executeArray,$_SESSION['radius']);
$searchStmt->execute($executeArray);
$searchResults = $searchStmt->fetchAll(PDO::FETCH_ASSOC);

$genericMatches = searchQueryOutput($searchResults,'g');
$regMatches = searchQueryOutput($searchResults,'r');
$catID = getCatFromSubcat($_POST['subCat']);
$catName = getCategoryName($catID);
$unit = getUnit($_POST['subCat']);
$term = getSubcatName($_POST['subCat']);
$organic = $_POST['organic'];
$resType = 'unbranded';
include('views/subcatView.php');