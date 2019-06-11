<main id="brand-matches">
<h1>Brand Matches</h1>
<?php

if(isset($_POST['brandSelect'])){
    unset($info);
    if(isset($_POST['searchTerm'])){
        $name = $_POST['searchTerm'];    
    }    
    if(isset($_POST['matchType'])){
        if($_POST['matchType']==='b'){
            $brandID = $_POST['matchID'];
            $matchType = 'brand';
        } else if ($_POST['matchType'] === 'sb'){
            $brandID = getBrandIdFromSubbrand($_POST['matchID']);
            $matchType = 'subbrand';
        }
    }
} else {
    $name = $info['name'];
    if($matchType==='brand'){
        $brandID = $info['id'];
    } else if ($matchType === 'subbrand'){
        $brandID = getBrandIdFromSubbrand($info['id']);
    }
}

$subBrands = [];
foreach($results as $result){
    if(!$result['subbrand_id']){
        $subBrands[]='';
    } else {
        $subBrands[$result['subbrand_id']]=getSubbrandName($result['subbrand_id']);
    }    
}

?>
<form method="post" action="index.php" id="brand-match-form">
<input type="hidden" name="organic" id="organic" value="">
<input type="hidden" name="subcategory_id" id="subcategory_id" value="">
<input type="hidden" name="task" value="brand-match-select">
<input type="hidden" name="brand_id" value="<?php echo $brandID; ?>">
<input type="hidden" name="subbrand_id" value="" id="subbrand_id">
</form>
<?php
foreach($subBrands as $key=>$value){
    echo '<h2>'.getBrandName($brandID).($value?(' '.$value):('')).'</h2>';
    echo '<ul>';
    foreach($results as $result){
        if($result['subbrand_id']==$key ||($key==0 && !$result['subbrand_id'])){
            echo '<li><a href="#" data-subcatid="'.$result['subcategory_id'].'" data-organic="'.$result['item_organic'].'" data-subbrandid="'.($result['subbrand_id']?($result['subbrand_id']):('')).'">'.$result['subcategory_name'].'</a></li>';
        }
    }
    echo '</ul>';
}
?>
</main>