<?php

$siteRoot = 'http://grocery.tiffany-cissel.com/';


function inputValiScrub($input, $type='text') {
$input = trim(strip_tags($input));
$type = strtolower($type);
$valid = true;

if($type == 'email'){
    if(!filter_var($input,FILTER_VALIDATE_EMAIL)){
        $valid = false;
    }
} else if ($type = 'number'){
    if(is_numeric($input)){
        $input = $input + 0;
    } else {
        $valid = false;
    }
}

if($valid){
    return $input;
} else {
    return 0;
}
}

function multiWhere($dbCol,$variables,$type){
    if($type==='p'){
        $whereStmt = $dbCol.'=?';
        for($i = 1; $i<count($variables); $i++){
            $whereStmt.=' OR '.$dbCol.'=?';
        }
        return $whereStmt;
    } else if($type==='v'){
        $zips = [];
        foreach($variables as $variable){
            array_push($zips,$variable);
        }
        return $zips;
    }
}

function getMedian($numericArray){
    sort($numericArray);
    if(count($numericArray) % 2 == 0){
        $midHiIndex = count($numericArray)/2;
        $midLoIndex = $midHiIndex - 1;

        $median = ($numericArray[$midHiIndex] + $numericArray[$midLoIndex])/2;
    } else {
        $median = $numericArray[(count($numericArray))/2];
    }
    return $median;
}

function unitPriceFormat($price){
    if ($price<1){
        return '$'.number_format($price,3,'.',',');
    } else {
        return '$'.number_format($price,2,'.',',');
    }
}

function searchQueryOutput($array, $type){
    $rows = [];
    $unitPrices = [];
    $unitPricesNumeric = [];
    $results = [];
    if ($type === 'g'){
        foreach($array as $result){
            if($result['brand_id']==553 || $result['brand_id']==554){
                array_push($rows,$result);
                $unitPrices[$result['item_id']]=$result['unit_price'];
            } 
        }
    } else if($type === 'r'){
        foreach($array as $result){
            if($result['brand_id']!=553 && $result['brand_id']!=554){
                array_push($rows,$result);
                $unitPrices[$result['item_id']]=$result['unit_price'];
            } 
        }
    }

    if(!count($rows)){
        return null;
    } else {
        $unitPricesNumeric = $unitPrices;
    asort($unitPrices);
    sort($unitPricesNumeric);
    $results['min'] = min($unitPricesNumeric);
    $results['median'] = getMedian($unitPricesNumeric);
    $results['max'] = max($unitPricesNumeric);
    $results['minItemNum'] = array_search($results['min'],$unitPrices);
    $results['maxItemNum'] = array_search($results['max'],$unitPrices);
    $results['min'] = unitPriceFormat($results['min']);
    $results['median'] = unitPriceFormat($results['median']);
    $results['max'] = unitPriceFormat($results['max']);

    for($i = 0; $i<count($rows); $i++){
        if($rows[$i]['item_id']==$results['minItemNum']){
            $minRow = $i;
            break;
        }
    }

    for($i = 0; $i<count($rows); $i++){
        if($rows[$i]['item_id']==$results['maxItemNum']){
            $maxRow = $i;
            break;
        }
    }

    $results['minInfo'] = $rows[$minRow];
    $results['maxInfo'] = $rows[$maxRow];
    if($type==='r'){
        $results['minBrand'] = $results['minInfo']['brand_name'];
        $results['maxBrand'] = $results['maxInfo']['brand_name'];
    }
    $results['minStore'] = $results['minInfo']['store_name'];    
    $results['maxStore'] = $results['maxInfo']['store_name'];

    $results['subcatID'] = $array[0]['subcategory_id'];

    return $results;
    }    
}

function searchQuerySpread($array, $type){
    //type can be 'detail' (for info on each brand) or 'summary' (overall min, max med of generic and brand name)
    if($type==='summary'){
        $genBrandAllPrices = ['gen'=>[],'brand'=>[]];
        $summary = ['gen'=>[],'brand'=>[],'subcat'=>[]];
        $info = [];
        $summary['subcat']['id'] = $array[0]['subcategory_id'];
        foreach($array as $row){
            if($row['brand_id']==553 || $row['brand_id']==554){
                $genBrandAllPrices['gen'][]=$row['unit_price'];
            }  else {
                $genBrandAllPrices['brand'][]=$row['unit_price'];
            }
        }
        if(count($genBrandAllPrices['gen'])>0){
            $summary['gen']['min']=min($genBrandAllPrices['gen']);
            $summary['gen']['max']=max($genBrandAllPrices['gen']);
            $summary['gen']['med']=getMedian($genBrandAllPrices['gen']);
        }
        if(count($genBrandAllPrices['brand'])>0){
            $summary['brand']['min']=min($genBrandAllPrices['brand']);       
            $summary['brand']['max']=max($genBrandAllPrices['brand']);        
            $summary['brand']['med']=getMedian($genBrandAllPrices['brand']);
        }
        return $summary;
    } else if ($type==='detail'){
        //handle items w/ subbrands separately from those w/o (separate aggregate arrays) and then merge the arrays
        $itemsStores = [];
        $brands = [];
        $brandNames = [];
        $subbrandNames = [];
        $brandsAllPrices = [];
        $brandsMaxMinMed = [];
        foreach($array as $row){
            $itemsStores[$row['item_id']]=$row['store_name'];
            $brands[]=$row['brand_id'];
            $brandNames[$row['brand_id']]=$row['brand_name'];
            if($row['subbrand_id']){
                if(!key_exists($row['subbrand_id'],$subbrandNames)){
                    $subbrandNames[$row['subbrand_id']] = getSubbrandName($row['subbrand_id']);
                }
                $brandsAllPrices[$row['brand_id']][$row['subbrand_id']][$row['item_id']]=$row['unit_price'];                
            } else {
                $brandsAllPrices[$row['brand_id']][''][$row['item_id']]=$row['unit_price'];    
            }
        }
        foreach($brandsAllPrices as $key=>$value){
            foreach($value as $subkey=>$subvalue){
                $brandsMaxMinMed[$key][$subkey]['minPrice'] = min($subvalue);
                $brandsMaxMinMed[$key][$subkey]['minItemID'] = array_search(min($subvalue),$brandsAllPrices[$key][$subkey]);
                $brandsMaxMinMed[$key][$subkey]['minStore'] = $itemsStores[$brandsMaxMinMed[$key][$subkey]['minItemID']];
                $brandsMaxMinMed[$key][$subkey]['maxPrice'] = max($subvalue);
                $brandsMaxMinMed[$key][$subkey]['maxItemID'] = array_search(max($subvalue),$brandsAllPrices[$key][$subkey]);
                $brandsMaxMinMed[$key][$subkey]['maxStore'] = $itemsStores[$brandsMaxMinMed[$key][$subkey]['maxItemID']];
                $brandsMaxMinMed[$key][$subkey]['medianPrice'] = getMedian($brandsAllPrices[$key][$subkey]);
                $brandsMaxMinMed[$key][$subkey]['brandName'] = $brandNames[$key];
                if($subkey){
                    $brandsMaxMinMed[$key][$subkey]['subbrandName'] = $subbrandNames[$subkey];
                }else{
                    $brandsMaxMinMed[$key][$subkey]['subbrandName'] = '';
                }               
            }
        }
        return $brandsMaxMinMed;
    }    
}

function brandQuery($catID, $subCatID, $db, $type, $brandIDs='', $relatedIDs=''){
     //type can be "c" for brands currently included within category, "r" for related brands or "o" for other brands  
    if($type === 'c'){
        $simpleBrands = [];
        if($catID !== 3){
            $brandQuery = 'SELECT DISTINCT i.brand_id, b.brand_name FROM items i INNER JOIN brands b ON b.brand_id=i.brand_id WHERE subcategory_id = ANY(SELECT subcategory_id from subcategories where category_id=?) ORDER BY b.brand_name';
            $brandStmt = $db->prepare($brandQuery);
            $brandStmt->execute([$catID]);
            $brands = $brandStmt->fetchAll(PDO::FETCH_ASSOC);            
        } else {
            $brandQuery = 'SELECT DISTINCT i.brand_id, b.brand_name FROM items i INNER JOIN brands b ON b.brand_id=i.brand_id WHERE subcategory_id BETWEEN ? AND ?  ORDER BY b.brand_name';
            $brandStmt = $db->prepare($brandQuery);
            if($subCatID>=152 && $subCatID<=181){   
                $brandStmt->execute([152,181]);
            } elseif ($subCatID>=182 && $subCatID<=229) {
                $brandStmt->execute([182,229]);                
            }
            $brands = $brandStmt->fetchAll(PDO::FETCH_ASSOC);
        }
        foreach($brands as $key=>$value){
            $simpleBrands[$value['brand_id']]=$value['brand_name'];
        }
        if(!array_key_exists(553,$simpleBrands)){
            $simpleBrands[553]='Store Brand';
        }
        if(!array_key_exists(554,$simpleBrands)){
            $simpleBrands[554]='Store Brand, Premium';
        }
        asort($simpleBrands);
        return $simpleBrands;
    } else if ($type === 'r') {
        $relatedBrands = [];
        $relatedBrandsSimple = [];
        $brandQuery = 'SELECT brand_id, brand_name FROM brands where brand_type=? and not ('.multiWhere('brand_id',$brandIDs,'p').') order by brand_name';    
        $brandStmt = $db->prepare($brandQuery);

        if($catID!==3){
            if($catID==2 || $catID==5 || $catID==6 || $catID==7 || $catID==9 || $catID==12 || $catID==14){                
                $execArray = ['g'];
            } elseif($catID==10 || $catID==11 || $catID==15 || $catID==20){
                $execArray = ['r'];
            } elseif($catID==1) {
                $execArray = ['i'];
            } elseif($catID==4) {
                $execArray = ['d'];                
            } elseif($catID==8) {
                $execArray = ['c'];                
            } elseif($catID==13) {
                $execArray = ['f'];                
            } elseif($catID==17) {
                $execArray = ['p'];                
            } elseif($catID==18) {
                $execArray = ['h'];                
            } elseif($catID==19) {
                $execArray = ['z'];                
            } elseif($catID==21) {
                $execArray = ['s'];                
            }           
        } elseif($catID===3) {
            if($subCatID>=152 && $subCatID<=181){
                $execArray = ['b'];  
            } elseif($subCatID>=182 && $subCatID<=229){
                $execArray = ['w'];  
            } 
        }
        //shared db steps
        $execArray = array_merge($execArray,$brandIDs);
        $brandStmt->execute($execArray);
        $relatedBrands = $brandStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($relatedBrands as $key=>$value){
            $relatedBrandsSimple[$value['brand_id']]=$value['brand_name'];
        }
        asort($relatedBrandsSimple);
        return $relatedBrandsSimple;
    } else if ($type === 'o') {
        $otherBrands = [];
        $otherBrandsSimple = [];
        $usedIDs = array_merge($brandIDs,$relatedIDs);
        $otherBrandQuery = 'SELECT brand_id, brand_name FROM brands where not ('.multiWhere('brand_id',$usedIDs,'p').') order by brand_name';$otherBrandStmt = $db->prepare($otherBrandQuery);
        $otherBrandStmt->execute($usedIDs);
        $otherBrands = $otherBrandStmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($otherBrands as $key=>$value){
            $otherBrandsSimple[$value['brand_id']]=$value['brand_name'];
        }
        return $otherBrandsSimple;
    }
}

function getCategoryName($catID){
    $categories = [1=>'Baby', 2=>'Baking & Seasoning', 3=>'Beer & Wine', 4=>'Beverage', 5=>'Bread', 6=>'Breakfast', 7=>'Canned & Packaged', 8=>'Cleaning', 9=>'Condiments & Sauces', 10=>'Dairy', 11=>'Deli', 12=>'Ethnic', 13=>'Frozen', 14=>'Grains, Pasta,Potatoes', 15=>'Meat & Seafood', 16=>'Miscellaneous', 17=>'Paper & Plastics', 18=>'Personal Care & Cosmetics', 19=>'Pet', 20=>'Produce', 21=>'Snacks & Candy'];
    return($categories[$catID]);
}

function getUnit($subCatID){
    global $conn;
    $unitStmt = $conn->prepare('SELECT subcategory_unit AS unit FROM subcategories WHERE subcategory_id=?');
    $unitStmt->execute([$subCatID]);
    $result = $unitStmt->fetch(PDO::FETCH_ASSOC);
    return $result['unit'];
}

function getSubcatName($subCatID){
    global $conn;
    $unitStmt = $conn->prepare('SELECT subcategory_name AS name FROM subcategories WHERE subcategory_id=?');
    $unitStmt->execute([$subCatID]);
    $result = $unitStmt->fetch(PDO::FETCH_ASSOC);
    return $result['name'];
}

function getCatFromSubcat($subCatID){
    global $conn;
    $unitStmt = $conn->prepare('SELECT category_id AS id FROM subcategories WHERE subcategory_id=?');
    $unitStmt->execute([$subCatID]);
    $result = $unitStmt->fetch(PDO::FETCH_ASSOC);
    return $result['id'];
}

function getSubbrandName($subbrandID){
    global $conn;
    $stmt = $conn->prepare('SELECT subbrand_name FROM subbrands WHERE subbrand_id=?');
    $stmt->execute([$subbrandID]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['subbrand_name'];
}

function getBrandIdFromSubbrand($subbrandID){
    global $conn;
    $stmt = $conn->prepare('SELECT brand_id FROM subbrands WHERE subbrand_id=?');
    $stmt->execute([$subbrandID]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['brand_id'];
}

function getBrandName($brandID){
    global $conn;
    $stmt = $conn->prepare('SELECT brand_name FROM brands WHERE brand_id=?');
    $stmt->execute([$brandID]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['brand_name'];
}

function getStores(){
    global $conn;
    $stores = [];
    $stmt = $conn->prepare('SELECT store_id, store_name FROM stores');
    $stmt->execute();
    $result = $stmt->fetchAll();
    foreach($result as $store){
        $stores[$store['store_id']] = $store['store_name'];
    }
    return $stores;
}

function logEmail($paramArray){
    $queryUpdateFields = 'email_recip, email_topic';
    $queryUpdateValueMarks = '?, ?';
    $queryUpdateValues = [$paramArray['email'],$paramArray['topic']];

    
    if($paramArray['topic']=='reg' || $paramArray['topic']=='reset'){
        
    } else if ($paramArray['topic']=='fdbk'){
        $queryUpdateFields = $queryUpdateFields.', email_fdbkSub, email_fdbkMsg';
        $queryUpdateValueMarks = $queryUpdateValueMarks.', ?, ?';
        array_push($queryUpdateValues,$paramArray['fdbkSub'],$paramArray['fdbkMsg']);
    }
    if(!empty($paramArray['userID'])){
        $queryUpdateFields = $queryUpdateFields.', email_user_id';
        $queryUpdateValueMarks = $queryUpdateValueMarks.', ?';
        array_push($queryUpdateValues,$paramArray['userID']);
    }

    include('dbconn.php');

    $query = 'INSERT INTO sentEmail ('.$queryUpdateFields.') VALUES ('.$queryUpdateValueMarks.')';
    $stmt = $conn->prepare($query);
    $stmt -> execute($queryUpdateValues);
    // return $stmt->rowCount();
}

function randomRegGen($length=25){
    $asciiCodes = [48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122];
    //with symbols 33-47
    // $asciiCodes = [33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70,71,72,73,74,75,76,77,78,79,80,81,82,83,84,85,86,87,88,89,90,97,98,99,100,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122];
    $randString = '';
    for($i=0; $i<$length; $i++){
        $randString = $randString.chr($asciiCodes[mt_rand(0,(count($asciiCodes)-1))]);
    }
    $randString = htmlentities($randString);
    return $randString;
}