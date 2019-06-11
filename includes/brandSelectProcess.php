<?php

$searchQuery = 'SELECT item_id, i.brand_id, subbrand_id, subcategory_id, item_price, item_size, i.store_id,store_zip, item_organic, (item_price/item_size) AS unit_price, brand_name, store_name FROM items i INNER JOIN brands b ON b.brand_id = i.brand_id INNER JOIN stores s ON s.store_id = i.store_id where subcategory_id=? AND item_organic=? AND i.brand_id=? AND ('.multiWhere('store_zip',$_SESSION['radius'],'p').') order by item_added DESC';
$searchStmt = $conn->prepare($searchQuery);
$executeArray = [$_POST['subcategory_id'],$_POST['organic'],$_POST['brand_id']];
$executeArray = array_merge($executeArray,$_SESSION['radius']);
$searchStmt->execute($executeArray);
$searchResults = $searchStmt->fetchAll(PDO::FETCH_ASSOC);

$regMatches = searchQueryOutput($searchResults,'r');
$resType = 'branded';
$catName = getCategoryName(getCatFromSubcat($_POST['subcategory_id'])); 
$unit = getUnit($_POST['subcategory_id']);
$organic = $_POST['organic'];
$term = getSubcatName($_POST['subcategory_id']);

$info = ['catID'=>getCatFromSubcat($_POST['subcategory_id']), 'catName'=>getCategoryName(getCatFromSubcat($_POST['subcategory_id'])), 'unit'=>getUnit($_POST['subcategory_id']),'subcatID'=>$_POST['subcategory_id']];

if(isset($_POST['subbrand_id'])){
    $info['id']=$_POST['subbrand_id'];
    $info['type']='subbrand';
    $info['brandID']=$_POST['brand_id'];
} else {
    $info['id']=$_POST['brand_id'];
    $info['type']='brand';
    $info['brandID']=$_POST['brand_id'];
}

include('views/subcatView.php');