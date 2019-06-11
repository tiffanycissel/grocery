i<?php
include('zipArray.php');

if(isset($_GET['zip'])){
    $zipLatLon = allZips($_GET['zip']);
    echo json_encode($zipLatLon);
} else {
    $response = ['lat'=>'x','lon'=>'x'];
    echo json_encode($response);

}

?>