<?php

include('dbconn.php');

if(isset($_GET['category_id']) && isset($_GET['task']) && $_GET['task']==='subcats'){
    $subCatQuery = 'SELECT subcategory_id, subcategory_name, subcategory_unit from subcategories WHERE category_id=?';
    $subCatStmt = $conn->prepare($subCatQuery);
    $subCatStmt->execute([$_GET['category_id']]);
    $subCats = $subCatStmt->fetchAll();
    echo json_encode($subCats);
    
}

if(isset($_GET['category_id']) && isset($_GET['task']) && $_GET['task']==='update'){
   
   $brandQuery = 'SELECT DISTINCT i.brand_id, b.brand_name FROM items i INNER JOIN brands b ON b.brand_id=i.brand_id WHERE subcategory_id = ANY(SELECT subcategory_id from subcategories where category_id=?) ORDER BY b.brand_name';

    $brandStmt = $conn->prepare($brandQuery);
    $brandStmt->execute([$_GET['category_id']]);
    $brands = $brandStmt->fetchAll();
    echo json_encode($brands);
}

if(isset($_GET['task']) && isset($_GET['term']) && $_GET['task']=='search'){
    echo json_encode(getSearchTerms2($conn,$_GET['term']));
}

if(isset($_GET['task']) && isset($_GET['brandID']) && isset($_GET['categoryID']) && $_GET['task']=='subbrands'){
    $stmt = $conn->prepare('SELECT DISTINCT subbrand_id, subbrand_name FROM subbrands INNER JOIN subcategories ON subcategories.subcategory_id = subbrands.subcategory_id WHERE brand_id = ? AND category_id=?');
    $stmt -> execute([$_GET['brandID'],$_GET['categoryID']]);
    $subbrands = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($subbrands);
}

if(isset($_POST['task']) && (isset($_POST['value'])) && (isset($_POST['action'])) && $_POST['task']=='saveChanges') {
    if (session_status() == PHP_SESSION_NONE){
        session_start();
    }
    $newVal = $_POST['value'];
    $action = $_POST['action'];
    

    if($action =='email'){
        $query = 'UPDATE users SET user_email = ? WHERE user_id = ?';
    } else if ($action == 'zip'){
        $query = 'UPDATE users SET user_zip = ? WHERE user_id = ?';
    } else if ($action == 'radius'){
        $query = 'UPDATE users SET user_radius = ? WHERE user_id = ?';
    }
    $stmt = $conn->prepare($query);
    $stmt->execute([$newVal, $_SESSION['userID']]);
}
if(isset($_POST['task']) && (isset($_POST['userID'])) && (isset($_POST['brandID'])) && (isset($_POST['subbrandID'])) && (isset($_POST['subcategoryID'])) && (isset($_POST['organic'])) && $_POST['task']=='addFavorite') {
    $userID = $_POST['userID'];
    $brandID = $_POST['brandID'];
    $subbrandID = $_POST['subbrandID'];
    $subcategoryID = $_POST['subcategoryID'];
    $organic = $_POST['organic'];

    $query = "INSERT INTO favorites (user_id, brand_id, subbrand_id, subcategory_id, item_organic) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($query);
    $stmt->execute([$userID, $brandID, $subbrandID, $subcategoryID, $organic]);
    $result = $conn->lastInsertID();
    echo json_encode($result);
}

if(isset($_POST['task']) && (isset($_POST['userID'])) && (isset($_POST['faveID'])) && $_POST['task']=='removeFavorite') {
    $userID = $_POST['userID'];
    $faveID = $_POST['faveID'];

    $query = "DELETE FROM favorites WHERE user_id = ? AND favorite_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$userID, $faveID]);

    $result = $stmt->rowCount();
    echo json_encode($result);
}

if(isset($_GET['task']) && $_GET['task']=='updates'){
    if (session_status() == PHP_SESSION_NONE){
        session_start();
    }
    $offset = $_GET['offset'];
    $query = 'SELECT i.item_id, b.brand_name, sb.subbrand_name, sc.subcategory_name, s.store_name, i.item_price, i.item_size, i.store_zip, i.item_organic, c.category_name, sc.subcategory_unit, i.item_added from items i LEFT JOIN brands b ON i.brand_id = b.brand_id LEFT JOIN subbrands sb ON sb.subbrand_id = i.subbrand_id LEFT JOIN subcategories sc ON i.subcategory_id = sc.subcategory_id LEFT JOIN stores s ON i.store_id = s.store_id LEFT JOIN categories c ON sc.category_id = c.category_id WHERE i.item_id IN (SELECT item_id FROM items WHERE user_id = ? ORDER BY item_added desc) ORDER BY item_added desc LIMIT 50 OFFSET '.$offset;
    $stmt = $conn->prepare($query);
    $stmt -> execute([$_SESSION['userID']]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $resultRows = [];
    $rowCount = 1;

    echo json_encode($results);    
}

if(isset($_GET['task']) && $_GET['task']=='updatesTotal'){
    if (session_status() == PHP_SESSION_NONE){
        session_start();
    }
    $query = 'SELECT COUNT(item_id) FROM items WHERE user_id = ?';
    $stmt = $conn->prepare($query);
    $stmt -> execute([$_SESSION['userID']]);
    $result = $stmt->fetch();
    echo $result[0];
}

if(isset($_GET['task']) && $_GET['task']=='getAPI'){
    //api key removed for security
    $apiInfo = '';
    echo $apiInfo;
}

if(isset($_GET['task']) && $_GET['task']=='subcatQuantities'){    
    $minQuery = 'SELECT MIN(item_size) as min from items WHERE subcategory_id=?';
    $minStmt = $conn->prepare($minQuery);
    $minStmt -> execute([$_GET['subcatID']]);
    $minResults = $minStmt->fetch(PDO::FETCH_ASSOC);

    $maxQuery = 'SELECT MAX(item_size) as max from items WHERE subcategory_id=?';
    $maxStmt = $conn->prepare($maxQuery);
    $maxStmt -> execute([$_GET['subcatID']]);
    $maxResults = $maxStmt->fetch(PDO::FETCH_ASSOC);

    $minPrQuery = 'SELECT MIN(item_price) as minPr from items WHERE subcategory_id=?';
    $minPrStmt = $conn->prepare($minPrQuery);
    $minPrStmt -> execute([$_GET['subcatID']]);
    $minPrResults = $minPrStmt->fetch(PDO::FETCH_ASSOC);

    $maxPrQuery = 'SELECT MAX(item_price) as maxPr from items WHERE subcategory_id=?';
    $maxPrStmt = $conn->prepare($maxPrQuery);
    $maxPrStmt -> execute([$_GET['subcatID']]);
    $maxPrResults = $maxPrStmt->fetch(PDO::FETCH_ASSOC);

    $results = array_merge($minResults,$maxResults, $minPrResults, $maxPrResults);

    echo json_encode($results);    
}

//functions
function getSearchTermBrands2($db,$term){
    $terms = preg_split("/(\s|,\s)/",$term);
    $trmFirstGrp = '(';
    $trmSecGrp = '(';
    $termExec = [];
    foreach($terms as $key=>$value){
        if($key==0){
            $trmFirstGrp.='replace(keywords.name,"\'","") LIKE ? ';
            $trmSecGrp.='s.subcategory_name LIKE ? ';
        } else {
            $trmFirstGrp.=' AND replace(keywords.name,"\'","") LIKE ? ';
            $trmSecGrp.=' AND s.subcategory_name LIKE ? ';
        }
        $termExec[] = '%'.$value.'%';
    }
    $trmFirstGrp.=')';
    $trmSecGrp.=')';
    
    $query = 
    'SELECT * FROM ( SELECT * from (SELECT brand_id as ID, brand_name as name, "b" as type FROM brands UNION SELECT s.subbrand_id, concat(b.brand_name," ",s.subbrand_name COLLATE utf8_general_ci), "sb" from subbrands s INNER JOIN brands b ON b.brand_id = s.brand_id ) keywords WHERE'. $trmFirstGrp .'ORDER BY keywords.name ) results UNION SELECT i.brand_id, b.brand_name, "b" from items i INNER JOIN brands b ON b.brand_id = i.brand_id INNER JOIN subcategories s ON s.subcategory_id=i.subcategory_id WHERE'.$trmSecGrp.'AND NOT (i.brand_id=553 OR i.brand_id=554) ORDER BY name
    ';
    $stmt = $db->prepare($query);
    $termMerge = array_merge($termExec,$termExec);
    $stmt->execute($termMerge);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $output = [];   

    foreach($results as $result){
        $output[] = array('id'=>$result['ID'], 'name'=>$result['name'], 'type'=>$result['type']);
    }
    return $output;
}

function getSearchSubs2($db,$term){
    $terms = preg_split("/(\s|,\s)/",$term);
    $trmGrp = '(';
    $termExec = [];
    foreach($terms as $key=>$value){
        if($key==0){
            $trmGrp.='s.subcategory_name LIKE ? ';
        } else {
            $trmGrp.=' AND s.subcategory_name LIKE ? ';
        }
        $termExec[] = '%'.$value.'%';
    }
    $trmGrp.=')';
    $query = 
    'SELECT s.subcategory_id AS subID, s.subcategory_name AS name, c.category_id as catID, c.category_name AS catName, "sc" as type FROM subcategories s INNER JOIN categories c ON c.category_id=s.category_id WHERE'.$trmGrp.'ORDER BY c.category_name, s.subcategory_name
    ';
    $stmt = $db->prepare($query);
    $stmt->execute($termExec);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $output = [];

    foreach($results as $result){
        $output[] = array('id'=>$result['subID'], 'name'=>$result['name'], 'type'=>$result['type'].'/'.$result['catID']);
    }
    return $output;    
}

function getSearchTerms2($db,$term){
    $results = array_merge(getSearchTermBrands2($db,$term), getSearchSubs2($db,$term));
    return $results;
}
