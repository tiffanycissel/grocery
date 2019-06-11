<?php

// values to submit to DB
$theUserID = !isset($_SESSION['userID']) ? 'test' : $_SESSION['userID'];

$updateSubmissionValues = [
'subCat' => $_POST['subCat'],
'brand' => $_POST['brand'],
'size' => filter_var($_POST['size'], FILTER_SANITIZE_NUMBER_FLOAT),
'unit' => $_POST['unit'],
'organic' => $_POST['organic'],
'price' => (filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT))*.01,
'store' => $_POST['storeName'],
'storeZip' => $_POST['storeZip'],
'userID' => $theUserID,
];

if(isset($_POST['subbrand'])){
    $updateSubmissionValues['subbrand'] = $_POST['subbrand'];
} else {
    $updateSubmissionValues['subbrand'] = null;
}

$stores = getStores();

$query = "INSERT INTO items (brand_id, subbrand_id, subcategory_id, item_price, store_id, store_zip, item_organic, item_size) VALUES (?, ?, ?, ?, ?, ?,?, ?);";

$valuesArray = [$updateSubmissionValues['brand'],$updateSubmissionValues['subbrand'],$updateSubmissionValues['subCat'],$updateSubmissionValues['price'],$updateSubmissionValues['store'],$updateSubmissionValues['storeZip'],$updateSubmissionValues['organic'],$updateSubmissionValues['size']];

$insertStatement = $conn->prepare($query);
$insertStatement->execute($valuesArray);
$sucessfulSubmisson = $insertStatement->rowCount();

if($sucessfulSubmisson!=0){
    $rowID = $conn->lastInsertID();
    $tsQuery = 'SELECT item_added FROM items WHERE item_id = ?';
    $tsSearchStmt = $conn->prepare($tsQuery);
    $tsSearchStmt->execute(array($rowID));
    $tsResult = $tsSearchStmt->fetch(PDO::FETCH_ASSOC);
}

$success = "<p>Your update was successful!<br>Here's the details:</p>".
            '<ul>'.
            '<li>User: '.$_SESSION['userEmail'].'</li>'.
            '<li>Item: '.getCategoryName(getCatFromSubcat($updateSubmissionValues['subCat'])).', '.getSubcatName($updateSubmissionValues['subCat']).', '.getBrandName($updateSubmissionValues['brand']).(!$updateSubmissionValues['subbrand']? '' : (' '.getSubbrandName($updateSubmissionValues['subbrand']))).($updateSubmissionValues['organic'] == 0 ? '' : ', Organic').'</li>'.
            '<li>Size: '.$updateSubmissionValues['size'].' '.$updateSubmissionValues['unit'].'</li>'.
            '<li>Price: $'.$updateSubmissionValues['price'].'</li>'.
            '<li>Store: '.$stores[$updateSubmissionValues['store']].', '.$updateSubmissionValues['storeZip'].'</li>'.
            '<li>Timestamp: '.$tsResult['item_added'].'</li>'.
            '</ul>'.
            '<p>Thanks for helping out!</p>';
$failure = '<p>Sorry, but something went wrong with your update. Please try again.</p>';

if($sucessfulSubmisson!=0){
    $message = $success;
} else {
    $message = $failure;
}

?>

<main id="submitUpdate">
    
    <h2>Update Submission</h2>
    <!--success/failure paragraph-->
    <?php echo $message; ?>
    <a href="?page=home" role="button">Return to Home</a>
    <a href="?page=search" role="button">Search for Items</a>
    <a href="#" role="button">My Profile</a>
</main>
