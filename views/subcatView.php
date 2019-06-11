<main id="subcat-view">

<?php
if(isset($_POST['task'])){
    if($_POST['task']==='brand-match-select'){
        if(empty($_POST['subbrand_id'])){
            $info['type'] = 'brand';
            $info['id'] = $_POST['brand_id'];
        } else {
            $info['type'] = 'subbrand';
            $info['id'] = $_POST['subbrand_id'];   
        }
    }
}

$subcatID = '';
$infoType = $resType;
$infoTypeSpread = [];
$infoPrices = ['gen-lo'=>'','gen-med'=>'','gen-hi'=>'','brand-lo'=>'','brand-med'=>'','brand-hi'=>''];

if(empty($searchResults)){
    $infoType = 'empty';
    if(isset($info)){
        $subcatID = $info['subcatID'];
    } else if (isset($_POST['subcatID'])){
        $subcatID = $_POST['subcatID'];
    } else if (isset($_POST['subCat'])){
        $subcatID = $_POST['subCat'];
    }

echo '<p id="matchCrumbs" data-infoType="empty">'.$catName.' > '.getSubcatName($subcatID).($organic ? ', Organic':'').'</p>';
echo '<h2>Good Price</h2>';

if(!$genericMatches){
    echo '<p>Store brand: No store brand info</p>';
} 
if(!$regMatches){
    echo '<p>Name brand: No brand name info</p>';
}

    echo '<h2>Price Detail</h2>
    <p>No info yet for '.getSubcatName($subcatID).'</p>';

} else {
    $infoTypeSpread = searchQuerySpread($searchResults,'summary');

if($resType==='unbranded'){    
    if(isset($info)){
        $subcatID = $info['subcatID'];
    } else if (isset($_POST['subcatID'])){
        $subcatID = $_POST['subcatID'];
    } else if (isset($_POST['subCat'])){
        $subcatID = $_POST['subCat'];
    }    
    $priceDetail = searchQuerySpread($searchResults,'detail');
    $priceSummary = searchQuerySpread($searchResults,'summary'); 
    $infoAmtsSpread = $priceDetail;

echo '<p id="matchCrumbs" data-infoType="unbranded">'.$catName.' > '.$term.($organic ? ', Organic':'').'</p>';
echo '<h2>Good Price</h2>';

if(!$genericMatches){
    echo '<p>Store brand: No store brand info</p>';
} else {
    echo '<p>Store brand: '.unitPriceFormat($priceSummary['gen']['min']).'-'.unitPriceFormat($priceSummary['gen']['med']).'/'.$unit.'</p>';
}
if(!$regMatches){
    echo '<p>Name brand: No brand name info</p>';
} else {
    echo '<p>Name brand: '.unitPriceFormat($priceSummary['brand']['min']).'-'.unitPriceFormat($priceSummary['brand']['med']).'/'.$unit.'</p>';
}

echo '
<h2>Price Detail</h2>
<p>Prices are per '.$unit.'</p>
<table class="detail-table">
    <thead>
        <tr>
            <th>Brand</th>
            <th>Good Price</th>
            <th>Low</th>
            <th>High</th>
        </tr>
    </thead>
    <tbody>';
    foreach($priceDetail as $key=>$value){
        foreach($value as $subkey=>$row){
            echo '<tr>
            <td class="brand-cell">';
            if($key!=553 && $key!=554){
                echo '<a href="#" ';
                if($subkey){
                    echo 'data-matchtype="sb" data-matchid="'.$subkey.'">'.$row['brandName'].' '.$row['subbrandName'].'</a>'; 
                    $searchTerm = $row['brandName'].' '.$row['subbrandName'];
                } else {
                    echo 'data-matchtype="b" data-matchid="'.$key.'">'.$row['brandName'].'</a>';
                    $searchTerm = $row['brandName'];
                }
            } else {
                echo $row['brandName'];
            }       
            
            echo '</td>
            <td>'.unitPriceFormat($row['minPrice']).' - '.unitPriceFormat($row['medianPrice']).'</td>
            <td>'.unitPriceFormat($row['minPrice']).' at '.$row['minStore'].'</td>
            <td>'.unitPriceFormat($row['maxPrice']).' at '.$row['maxStore'].'</td>
        </tr>';
        
        }
    }
echo '
    </tbody>
</table>';

} else if ($resType==='branded'){

    $subcatID = $regMatches['subcatID'];

    $brandInfo = [];
    $brandInfo['id'] = $info['id'];

    if($info['type']==='brand'){
        $brandInfo['brandName'] = getBrandName($brandInfo['id']);
        $searchTerm = $brandInfo['brandName'];
    }else if ($info['type']==='subbrand'){
        $brandInfo['subbrandName'] = getSubbrandName($brandInfo['id']);
        $brandInfo['brandName'] = getBrandName(getBrandIdFromSubbrand($brandInfo['id']));
        $searchTerm = $brandInfo['brandName'].' '.$brandInfo['subbrandName'];
    }

    echo '<p id="matchCrumbs" data-infoType="branded">'.$catName.' > <a href="#" id="show-all-brands-link">'.$term.($organic ? ', Organic':'').'</a> > <a href="#" data-matchtype="b" data-matchid="'.$info['brandID'].'">'.$regMatches['maxBrand'].'</a>'.(isset($brandInfo['subbrandName'])?(' > <a href="#" data-matchtype="sb" data-matchid="'.$brandInfo['id'].'">'.$brandInfo['subbrandName'].'</a>'):('')).'</p>';

    if(!$regMatches){
        echo 'No matches';
    } else {
        echo '<h2>Price Detail</h2>
        <p>Prices are per '.$unit.'</p>';
        echo '<table class="detail-table">
        <thead>
            <tr>
                <th>Brand</th>
                <th>Good Price</th>
                <th>Low</th>
                <th>High</th>
            </tr>
        </thead>
        <tbody>
        <tr>';
        
        echo '<td>'.$regMatches['maxBrand'].(isset($brandInfo['subbrandName'])?$brandInfo['subbrandName']:'').'</td>';
        echo '<td>'.$regMatches['min'].'-'.$regMatches['median'].'</td>';
        echo '<td>'.$regMatches['min'].' at '.$regMatches['minStore'].'</td>';
        echo '<td>'.$regMatches['max'].' at '.$regMatches['maxStore'].'</td>';
        echo '</tr>
        </tbody>
        </table>';

    }
    echo '<form method="post" action="index.php" id="all-brands-form">
    <input type="hidden" name="task" value="showAllBrands">    
    <input type="hidden" name="subCat" value="'.$regMatches['subcatID'].'">
    <input type="hidden" name="organic" value="'.$organic.'">
    </form>';
}

}

if(isset($_SESSION['userID'])){
    $faveCheckQuery = 'SELECT favorite_id FROM favorites WHERE user_id=? AND brand_id=? AND subbrand_id=? AND subcategory_id=? AND item_organic=?';
    $faveCheckQueryVars = [];
    $faveCheckQueryVars[0]=$_SESSION['userID'];
    if($resType==='branded'){
        if($info['type']==='brand'){
            $faveCheckQueryVars[1]=$brandInfo['id'];
            $faveCheckQueryVars[2]=0;
        } else if ($info['type']==='subbrand'){
            $faveCheckQueryVars[1]=getBrandIdFromSubbrand($brandInfo['id']);
            $faveCheckQueryVars[2]=$brandInfo['id'];
        }
    } else {
        $faveCheckQueryVars[1]=0;
        $faveCheckQueryVars[2]=0;
    }
    $faveCheckQueryVars[3] = $subcatID;
    $faveCheckQueryVars[4] = $organic;
    
    $stmt = $conn->prepare($faveCheckQuery);
    $stmt->execute($faveCheckQueryVars);
    $result = $stmt->fetch();
    if(isset($result['favorite_id'])){
        $fave = true;
        $faveLink = 'Remove from Favorites';
        $faveID = $result['favorite_id'];
    } 
    else {
        $fave = false;
        $faveLink = 'Add to Favorites';
        $faveID = '';
    }
}


if(!empty($infoTypeSpread)){
    if(isset($infoTypeSpread['brand']['min'])){
        $infoPrices['brand-lo']=$infoTypeSpread['brand']['min'];
        $infoPrices['brand-med']=$infoTypeSpread['brand']['med'];
        $infoPrices['brand-hi']=$infoTypeSpread['brand']['max'];
    }
    if(isset($infoTypeSpread['gen']['min'])){
        $infoPrices['gen-lo']=$infoTypeSpread['gen']['min'];
        $infoPrices['gen-med']=$infoTypeSpread['gen']['med'];
        $infoPrices['gen-hi']=$infoTypeSpread['gen']['max'];
    }
}
if(!empty($searchResults)){
    echo '<a role="button" href="#" id="shelfComBtn">Shelf Compare</a>';
}

?>
<a role="button" href="?page=search">Search Again</a>
<?php 
if(isset($_SESSION['userID'])){
    echo '<a role="button" href="#" id="favoriteBtn" data-user-id="'.$_SESSION['userID'].'" data-favorite="'.$fave.'" data-fave-id="'.$faveID.'">'.$faveLink.'</a>';
}
?>
<form method="post" action="index.php">
    <input type="hidden" name="task" value="updateItem">
    <input type="hidden" name="subCat" value="<?php echo $subcatID; ?>">
    <input type="hidden" name="subCatName" value="<?php echo getSubcatName($subcatID); ?>">
    <input type="hidden" name="catName" value="<?php echo $catName; ?>">
    <input type="hidden" name="catID" value="<?php echo getCatFromSubcat($subcatID); ?>">
    <input type="hidden" name="unit" value="<?php echo $unit; ?>">
    <input type="hidden" name="organic" value="<?php echo $organic ?>">
    <input type="hidden" name="updateType" value="<?php echo $resType ?>">
    <?php if($resType==='branded'){
        if($info['type']==='brand'){
            echo '<input type="hidden" name="brandID" value="'.$brandInfo['id'].'">';
        } else if ($info['type']==='subbrand'){
            echo '<input type="hidden" name="brandID" value="'.getBrandIdFromSubbrand($brandInfo['id']).'">';
            echo '<input type="hidden" name="subbrandID" value="'.$brandInfo['id'].'">';
        }
        

    } ?>
    <input type="submit" value="Update Item">
</form>
<form id="show-brand" method="post" action="index.php">
    <input type="hidden" name="task" value="search-form-sub">
    <input type="hidden" name="user_zip" value="<?php echo $_SESSION['userZip']; ?>">
    <input type="hidden" name="searchTerm" value="" id="searchTerm">
    <input type="hidden" name="organic" value="<?php echo $organic; ?>">
    <input type="hidden" name="matchID" value="" id="matchIdInput">
    <input type="hidden" name="matchType" value="" id="matchTypeInput">
    <input type="hidden" name="subcatID" value="<?php echo $subcatID; ?>">
    <?php if($resType==='unbranded'){
        echo '<input type="hidden" name="brandSelect">';
    }
    ?>
</form>
</main>
<div id="shelfCompTool" class="hide">
<div data-gen-lo="<?php echo $infoPrices['gen-lo']; ?>" data-gen-med="<?php echo $infoPrices['gen-med']; ?>" data-gen-hi="<?php echo $infoPrices['gen-hi']; ?>" data-brand-lo="<?php echo $infoPrices['brand-lo']; ?>" data-brand-med="<?php echo $infoPrices['brand-med']; ?>" data-brand-hi="<?php echo $infoPrices['brand-hi']; ?>" data-infotype="<?php echo $infoType; ?>" data-unit="<?php echo $unit; ?>">
    <div class="compHeadGrp">    
        <h2>Shelf Compare</h2>
        <div id="compClose">&times;</div>
    </div>
    <p>Enter info for a product and see how its price compares with our info</p>
    <form>
        <label for="price">Price</label>
        <input type="number" step=".01" name="price" id="compPrice">
        <label for="size">Size (<?php echo $unit; ?>)</label>
        <input type="number" step=".01" name="size" id="compSz">
    </form>
    <button id="calcCompare">Compare</button>
    <div id="results">
        <p id="resUnitPrice"></p>
    </div>
</div>
</div>