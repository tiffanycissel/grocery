<?php
if(!function_exists('allZips')){
    function allZips($allInfo=true){
        $zipsCoords = [
        ];
    
        $justZips = [
        ];
    
    
        if($allInfo===true){
            return $zipsCoords;
        } else if($allInfo>500 && $allInfo < 99929) {
            return $zipsCoords[$allInfo];
        } else {
            return $justZips;
        }    
    }
}
