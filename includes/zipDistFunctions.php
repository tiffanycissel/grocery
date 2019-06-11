<?php

// Distance functions from movable-type.co.uk/scripts/latlong.html
// KM and MI radius values from wikipedia.org/wiki/Earth_radius
// toRadians formula from stackoverflow.com/questions/9705123/how-can-i-get-sin-cos-and-tan-to-use-degrees-instead-of-radians

include('zipArray.php');

function toRadians($angle){
    $radians = $angle * (pi() / 180);
    return $radians;
}

function haversine_distance($lat1, $lat2, $lon1, $lon2, $unit){
    if ($unit == 'm') {
        $R = 6371e3; // metres
    } else if ($unit == 'km') {
        $R = 6370; // km
    } else if ($unit == 'mi') {
        $R = 3957.5; //mi
    }

    $φ1 = toRadians($lat1);
    $φ2 = toRadians($lat2);
    $Δφ = toRadians($lat2 - $lat1);
    $Δλ = toRadians($lon2 - $lon1);

    $a = sin($Δφ / 2) * sin($Δφ / 2) +
    cos($φ1) * cos($φ2) *
    sin($Δλ / 2) * sin($Δλ / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $d = $R * $c;

    return $d;
}

function spherical_distance($lat1, $lat2, $lon1, $lon2, $unit){
    $φ1 = toRadians($lat1);
    $φ2 = toRadians($lat2);
    $Δλ = toRadians($lon2 - $lon1);

    if ($unit == 'm') {
        $R = 6371e3; // metres
    } else if ($unit == 'km') {
        $R = 6370; // km
    } else if ($unit == 'mi') {
        $R = 3957.5; //mi
    }

    $d = acos(sin($φ1) * sin($φ2) + cos($φ1) * cos($φ2) * cos($Δλ)) * $R;

    return $d;
}

function pythag_distance($lat1, $lat2, $lon1, $lon2, $unit){
    $φ1 = toRadians($lat1);
    $φ2 = toRadians($lat2);
    $λ1 = toRadians($lon1);
    $λ2 = toRadians($lon2);

    if ($unit == 'm') {
        $R = 6371e3; // metres
    } else if ($unit == 'km') {
        $R = 6370; // km
    } else if ($unit == 'mi') {
        $R = 3957.5; //mi
    }

    $x = ($λ2 - $λ1) * cos(($φ1 + $φ2) / 2);
    $y = ($φ2 - $φ1);
    $d = sqrt($x * $x + $y * $y) * $R;

    return $d;
}

function getCoord($zip,$type){

    $zips = allZips();

    if($type==='lat'){
        $output = $zips[$zip]['lat'];
    } else if ($type==='lon'){
        $output = $zips[$zip]['lon'];
    } else {
        $output = 'invalid type; must be lat or lon';
    }
    return $output;
}

function getState($zip) {
    $state='';
    if ($zip < 25000) {
        switch (true) {
            case $zip >= 500 && $zip <= 599:
                $state = 'NY';
                break;
            case $zip >= 1000 && $zip <= 2799:
                $state = 'MA';
                break;
            case $zip >= 2800 && $zip <= 2999:
                $state = 'RI';
                break;
            case $zip >= 3000 && $zip <= 3899:
                $state = 'NH';
                break;
            case $zip >= 3900 && $zip <= 4999:
                $state = 'ME';
                break;
            case $zip >= 5000 && $zip <= 5499:
                $state = 'VT';
                break;
            case $zip >= 5500 && $zip <= 5599:
                $state = 'MA';
                break;
            case $zip >= 5600 && $zip <= 5999:
                $state = 'VT';
                break;
            case $zip >= 6000 && $zip <= 6999:
                $state = 'CT';
                break;
            case $zip >= 7000 && $zip <= 8999:
                $state = 'NJ';
                break;
            case $zip >= 10000 && $zip <= 14999:
                $state = 'NY';
                break;
            case $zip >= 15000 && $zip <= 19699:
                $state = 'PA';
                break;
            case $zip >= 19700 && $zip <= 19999:
                $state = 'DE';
                break;
            case $zip >= 20000 && $zip <= 20099:
                $state = 'DC';
                break;
            case $zip >= 20100 && $zip <= 20199:
                $state = 'VA';
                break;
            case $zip >= 20200 && $zip <= 20599:
                $state = 'DC';
                break;
            case $zip >= 20600 && $zip <= 21299:
                $state = 'MD';
                break;
            case $zip >= 21400 && $zip <= 21999:
                $state = 'MD';
                break;
            case $zip >= 22000 && $zip <= 24699:
                $state = 'VA';
                break;
            case $zip >= 24700 && $zip <= 24999:
                $state = 'WV';
                break;
        }

    } else if ($zip < 50000) {
        switch (true) {
            case $zip >= 25000 && $zip <= 26699:
                $state = 'WV';
                break;
            case $zip >= 27000 && $zip <= 28999:
                $state = 'NC';
                break;
            case $zip >= 29000 && $zip <= 29999:
                $state = 'SC';
                break;
            case $zip >= 30000 && $zip <= 31999:
                $state = 'GA';
                break;
            case $zip >= 32000 && $zip <= 33999:
                $state = 'FL';
                break;
            case $zip >= 34100 && $zip <= 34299:
                $state = 'FL';
                break;
            case $zip >= 34400 && $zip <= 34499:
                $state = 'FL';
                break;
            case $zip >= 34600 && $zip <= 34799:
                $state = 'FL';
                break;
            case $zip >= 34900 && $zip <= 34999:
                $state = 'FL';
                break;
            case $zip >= 35000 && $zip <= 35299:
                $state = 'AL';
                break;
            case $zip >= 35400 && $zip <= 36999:
                $state = 'AL';
                break;
            case $zip >= 37000 && $zip <= 38599:
                $state = 'TN';
                break;
            case $zip >= 38600 && $zip <= 39799:
                $state = 'MS';
                break;
            case $zip >= 39800 && $zip <= 39999:
                $state = 'GA';
                break;
            case $zip >= 39900 && $zip <= 39999:
                $state = 'GA';
                break;
            case $zip >= 40000 && $zip <= 42799:
                $state = 'KY';
                break;
            case $zip >= 43000 && $zip <= 45999:
                $state = 'OH';
                break;
            case $zip >= 46000 && $zip <= 47099:
                $state = 'IN';
                break;
            case $zip >= 47100 && $zip <= 47199:
                $state = 'KY';
                break;
            case $zip >= 47200 && $zip <= 47999:
                $state = 'IN';
                break;
            case $zip >= 48000 && $zip <= 49999:
                $state = 'MI';
                break;
        }
    } else if ($zip < 75000) {
        switch (true) {
            case $zip >= 50000 && $zip <= 52899:
                $state = 'IA';
                break;
            case $zip >= 53000 && $zip <= 53299:
                $state = 'WI';
                break;
            case $zip >= 53400 && $zip <= 53599:
                $state = 'WI';
                break;
            case $zip >= 53700 && $zip <= 54999:
                $state = 'WI';
                break;
            case $zip >= 55000 && $zip <= 55199:
                $state = 'MN';
                break;
            case $zip >= 55300 && $zip <= 56699:
                $state = 'MN';
                break;
            case $zip >= 56700 && $zip <= 56799:
                $state = 'ND';
                break;
            case $zip >= 57000 && $zip <= 57799:
                $state = 'SD';
                break;
            case $zip >= 58000 && $zip <= 58899:
                $state = 'ND';
                break;
            case $zip >= 59000 && $zip <= 59999:
                $state = 'MT';
                break;
            case $zip >= 60000 && $zip <= 62099:
                $state = 'IL';
                break;
            case $zip >= 62200 && $zip <= 62999:
                $state = 'IL';
                break;
            case $zip >= 63000 && $zip <= 63199:
                $state = 'MO';
                break;
            case $zip >= 63300 && $zip <= 64199:
                $state = 'MO';
                break;
            case $zip >= 64400 && $zip <= 65899:
                $state = 'MO';
                break;
            case $zip >= 66000 && $zip <= 66299:
                $state = 'KS';
                break;
            case $zip >= 66400 && $zip <= 67999:
                $state = 'KS';
                break;
            case $zip >= 68000 && $zip <= 68199:
                $state = 'NE';
                break;
            case $zip >= 68300 && $zip <= 69399:
                $state = 'NE';
                break;
            case $zip >= 70000 && $zip <= 70199:
                $state = 'LA';
                break;
            case $zip >= 70300 && $zip <= 70899:
                $state = 'LA';
                break;
            case $zip >= 71000 && $zip <= 71499:
                $state = 'LA';
                break;
            case $zip >= 71600 && $zip <= 72999:
                $state = 'AR';
                break;
            case $zip >= 73000 && $zip <= 73199:
                $state = 'OK';
                break;
            case $zip >= 73300 && $zip <= 73399:
                $state = 'TX';
                break;
            case $zip >= 73400 && $zip <= 74199:
                $state = 'OK';
                break;
            case $zip >= 74300 && $zip <= 74999:
                $state = 'OK';
                break;
        }

    } else {
        switch (true) {
            case $zip >= 75000 && $zip <= 79999:
                $state = 'TX';
                break;
            case $zip >= 80000 && $zip <= 81699:
                $state = 'CO';
                break;
            case $zip >= 82000 && $zip <= 83199:
                $state = 'WY';
                break;
            case $zip >= 83200 && $zip <= 83899:
                $state = 'ID';
                break;
            case $zip >= 84000 && $zip <= 84799:
                $state = 'UT';
                break;
            case $zip >= 85000 && $zip <= 85099:
                $state = 'AZ';
                break;
            case $zip >= 85200 && $zip <= 85399:
                $state = 'AZ';
                break;
            case $zip >= 85500 && $zip <= 85799:
                $state = 'AZ';
                break;
            case $zip >= 85900 && $zip <= 86099:
                $state = 'AZ';
                break;
            case $zip >= 86300 && $zip <= 86599:
                $state = 'AZ';
                break;
            case $zip >= 87000 && $zip <= 87599:
                $state = 'NM';
                break;
            case $zip >= 87700 && $zip <= 88499:
                $state = 'NM';
                break;
            case $zip >= 88500 && $zip <= 88599:
                $state = 'TX';
                break;
            case $zip >= 88900 && $zip <= 89199:
                $state = 'NV';
                break;
            case $zip >= 89300 && $zip <= 89599:
                $state = 'NV';
                break;
            case $zip >= 89700 && $zip <= 89899:
                $state = 'NV';
                break;
            case $zip >= 90000 && $zip <= 90899:
                $state = 'CA';
                break;
            case $zip >= 91000 && $zip <= 92899:
                $state = 'CA';
                break;
            case $zip >= 93000 && $zip <= 96199:
                $state = 'CA';
                break;
            case $zip >= 96700 && $zip <= 96899:
                $state = 'HI';
                break;
            case $zip >= 96900 && $zip <= 96999:
                $state = 'GU';
                break;
            case $zip >= 97000 && $zip <= 97999:
                $state = 'OR';
                break;
            case $zip >= 98000 && $zip <= 98699:
                $state = 'WA';
                break;
            case $zip >= 98800 && $zip <= 99499:
                $state = 'WA';
                break;
            case $zip >= 99500 && $zip <= 99999:
                $state = 'AK';
                break;
        }
    }
    return $state;
}

function getNeighborStates($zip) {
    $states = [];
    $state = getState($zip);
    array_push($states,$state);
    switch ($state) {
        case 'AL':
            array_push($states,'TN');
            array_push($states,'MS');
            array_push($states,'GA');
            array_push($states,'FL');
            break;
        case 'AZ':
            array_push($states,'CA');
            array_push($states,'NV');
            array_push($states,'UT');
            array_push($states,'CO');
            array_push($states,'NM');
            break;
        case 'AR':
            array_push($states,'MO');
            array_push($states,'TN');
            array_push($states,'MS');
            array_push($states,'LA');
            array_push($states,'TX');
            array_push($states,'OK');
            break;
        case 'CA':
            array_push($states,'OR');
            array_push($states,'NV');
            array_push($states,'AZ');
            break;
        case 'CO':
            array_push($states,'WY');
            array_push($states,'NE');
            array_push($states,'KS');
            array_push($states,'OK');
            array_push($states,'NM');
            array_push($states,'AZ');
            array_push($states,'UT');
            break;
        case 'CT':
            array_push($states,'NY');
            array_push($states,'MA');
            array_push($states,'RI');
            break;
        case 'DE':
            array_push($states,'NJ');
            array_push($states,'PA');
            array_push($states,'MD');
            break;
        case 'DC':
            array_push($states,'VA');
            array_push($states,'MD');
            break;
        case 'FL':
            array_push($states,'GA');
            array_push($states,'AL');
            break;
        case 'GA':
            array_push($states,'NC');
            array_push($states,'SC');
            array_push($states,'FL');
            array_push($states,'AL');
            array_push($states,'TN');
            break;
        case 'ID':
            array_push($states,'WA');
            array_push($states,'OR');
            array_push($states,'NV');
            array_push($states,'UT');
            array_push($states,'WY');
            array_push($states,'MT');
            break;
        case 'IL':
            array_push($states,'WI');
            array_push($states,'IN');
            array_push($states,'KY');
            array_push($states,'MO');
            array_push($states,'IA');
            break;
        case 'IN':
            array_push($states,'MI');
            array_push($states,'OH');
            array_push($states,'KY');
            array_push($states,'IL');
            break;
        case 'IA':
            array_push($states,'MN');
            array_push($states,'WI');
            array_push($states,'IL');
            array_push($states,'MO');
            array_push($states,'NE');
            array_push($states,'SD');
            break;
        case 'KS':
            array_push($states,'NE');
            array_push($states,'MO');
            array_push($states,'OK');
            array_push($states,'CO');
            break;
        case 'KY':
            array_push($states,'IL');
            array_push($states,'IN');
            array_push($states,'OH');
            array_push($states,'WV');
            array_push($states,'VA');
            array_push($states,'TN');
            array_push($states,'MO');
            break;
        case 'LA':
            array_push($states,'TX');
            array_push($states,'AR');
            array_push($states,'MS');
            break;
        case 'ME':
            array_push($states,'NH');
            break;
        case 'MD':
            array_push($states,'VA');
            array_push($states,'WV');
            array_push($states,'PA');
            array_push($states,'NJ');
            array_push($states,'DC');
            break;
        case 'MA':
            array_push($states,'NH');
            array_push($states,'VT');
            array_push($states,'NY');
            array_push($states,'CT');
            array_push($states,'RI');
            break;
        case 'MI':
            array_push($states,'WI');
            array_push($states,'IN');
            array_push($states,'OH');
            break;
        case 'MN':
            array_push($states,'ND');
            array_push($states,'SD');
            array_push($states,'IA');
            array_push($states,'WI');
            break;
        case 'MS':
            array_push($states,'TN');
            array_push($states,'AL');
            array_push($states,'LA');
            array_push($states,'AR');
            break;
        case 'MO':
            array_push($states,'IA');
            array_push($states,'IL');
            array_push($states,'KY');
            array_push($states,'TN');
            array_push($states,'AR');
            array_push($states,'OK');
            array_push($states,'KS');
            array_push($states,'NE');
            break;
        case 'MT':
            array_push($states,'ID');
            array_push($states,'WY');
            array_push($states,'SD');
            array_push($states,'ND');
            break;
        case 'NE':
            array_push($states,'SD');
            array_push($states,'IA');
            array_push($states,'MO');
            array_push($states,'KS');
            array_push($states,'CO');
            array_push($states,'WY');
            break;
        case 'NV':
            array_push($states,'CA');
            array_push($states,'OR');
            array_push($states,'ID');
            array_push($states,'UT');
            array_push($states,'AZ');
            break;
        case 'NH':
            array_push($states,'ME');
            array_push($states,'MA');
            array_push($states,'VT');
            break;
        case 'NJ':
            array_push($states,'NY');
            array_push($states,'PA');
            array_push($states,'MD');
            break;
        case 'NM':
            array_push($states,'AZ');
            array_push($states,'UT');
            array_push($states,'CO');
            array_push($states,'OK');
            array_push($states,'TX');
            break;
        case 'NY':
            array_push($states,'VT');
            array_push($states,'MA');
            array_push($states,'CT');
            array_push($states,'NJ');
            array_push($states,'PA');
            break;
        case 'NC':
            array_push($states,'VA');
            array_push($states,'TN');
            array_push($states,'SC');
            array_push($states,'GA');
            break;
        case 'ND':
            array_push($states,'SD');
            array_push($states,'MN');
            array_push($states,'MT');
            break;
        case 'OH':
            array_push($states,'MI');
            array_push($states,'IN');
            array_push($states,'KY');
            array_push($states,'WV');
            array_push($states,'PA');
            break;
        case 'OK':
            array_push($states,'KS');
            array_push($states,'MO');
            array_push($states,'AR');
            array_push($states,'TX');
            array_push($states,'NM');
            array_push($states,'CO');
            break;
        case 'OR':
            array_push($states,'WA');
            array_push($states,'ID');
            array_push($states,'CA');
            array_push($states,'NV');
            break;
        case 'PA':
            array_push($states,'NY');
            array_push($states,'NJ');
            array_push($states,'DE');
            array_push($states,'MD');
            array_push($states,'WV');
            array_push($states,'OH');
            break;
        case 'RI':
            array_push($states,'CT');
            array_push($states,'MA');
            break;
        case 'SC':
            array_push($states,'NC');
            array_push($states,'GA');
            break;
        case 'SD':
            array_push($states,'ND');
            array_push($states,'MN');
            array_push($states,'IA');
            array_push($states,'NE');
            array_push($states,'WY');
            array_push($states,'MT');
            break;
        case 'TN':
            array_push($states,'NC');
            array_push($states,'GA');
            array_push($states,'AL');
            array_push($states,'MS');
            array_push($states,'AR');
            array_push($states,'MO');
            array_push($states,'KY');
            break;
        case 'TX':
            array_push($states,'NM');
            array_push($states,'OK');
            array_push($states,'AR');
            array_push($states,'LA');
            break;
        case 'UT':
            array_push($states,'NV');
            array_push($states,'ID');
            array_push($states,'WY');
            array_push($states,'CO');
            array_push($states,'NM');
            array_push($states,'AZ');
            break;
        case 'VT':
            array_push($states,'NH');
            array_push($states,'MA');
            array_push($states,'NY');
            break;
        case 'VA':
            array_push($states,'MD');
            array_push($states,'DC');
            array_push($states,'WV');
            array_push($states,'KY');
            array_push($states,'TN');
            array_push($states,'NC');
            break;
        case 'WA':
            array_push($states,'ID');
            array_push($states,'OR');
            break;
        case 'WV':
            array_push($states,'OH');
            array_push($states,'PA');
            array_push($states,'MD');
            array_push($states,'VA');
            array_push($states,'KY');
            break;
        case 'WI':
            array_push($states,'MI');
            array_push($states,'IL');
            array_push($states,'IA');
            array_push($states,'MN');
            break;
        case 'WY':
            array_push($states,'MT');
            array_push($states,'SD');
            array_push($states,'NE');
            array_push($states,'CO');
            array_push($states,'UT');
            array_push($states,'ID');
            break;

    }
    sort($states);
    return $states;
}

function getFullRange($state=''){
    $rangesFull = [
    'AK'=> '99500-99999',
    'AL'=> '35000-35299,35400-36999',
    'AR'=> '71600-72999',
    'AZ'=> '85000-85099,85200-85399,85500-85799,85900-86099,86300-86599',
    'CA'=> '90000-90899,91000-92899,93000-96199',
    'CO'=> '80000-81699',
    'CT'=> '6000-6999',
    'DC'=> '20000-20099,20200-20599',
    'DE'=> '19700-19999',
    'FL'=> '32000-33999,34100-34299,34400-34499,34600-34799,34900-34999',
    'GA'=> '30000-31999,39800-39999,39900-39999',
    'HI'=> '96700-96899',
    'IA'=> '50000-52899',
    'ID'=> '83200-83899',
    'IL'=> '60000-62099,62200-62999',
    'IN'=> '46000-47099,47200-47999',
    'KS'=> '66000-66299,66400-67999',
    'KY'=> '40000-42799,47100-47199',
    'LA'=> '70000-70199,70300-70899,71000-71499',
    'MA'=> '1000-2799,5500-5599',
    'MD'=> '20600-21299,21400-21999',
    'ME'=> '3900-4999',
    'MI'=> '48000-49999',
    'MN'=> '55000-55199,55300-56699',
    'MO'=> '63000-63199,63300-64199,64400-65899',
    'MS'=> '38600-39799',
    'MT'=> '59000-59999',
    'NC'=> '27000-28999',
    'ND'=> '56700-56799,58000-58899',
    'NE'=> '68000-68199,68300-69399',
    'NH'=> '3000-3899',
    'NJ'=> '7000-8999',
    'NM'=> '87000-87599,87700-88499',
    'NV'=> '88900-89199,89300-89599,89700-89899',
    'NY'=> '10000-14999,500-599',
    'OH'=> '43000-45999',
    'OK'=> '73000-73199,73400-74199,74300-74999',
    'OR'=> '97000-97999',
    'PA'=> '15000-19699',
    'RI'=> '2800-2999',
    'SC'=> '29000-29999',
    'SD'=> '57000-57799',
    'TN'=> '37000-38599',
    'TX'=> '73300-73399,75000-79999,88500-88599',
    'UT'=> '84000-84799',
    'VA'=> '20100-20199,22000-24699',
    'VT'=> '5000-5499,5600-5999',
    'WA'=> '98000-98699,98800-99499',
    'WI'=> '53000-53299,53400-53599,53700-54999',
    'WV'=> '24700-26699,24700-26899',
    'WY'=> '82000-83199'
    ];

    if (!$state){
        return $rangesFull;
    } else {
        return $rangesFull[$state];
    }
}

function getSimpleRange($state=''){
    $rangesSimple = [
    'AK'=> array('lo'=> 99500, 'hi'=> 99999),
    'AL'=> array('lo'=> 35000, 'hi'=> 36999),
    'AR'=> array('lo'=> 71600, 'hi'=> 72999),
    'AZ'=> array('lo'=> 85000, 'hi'=> 86599),
    'CA'=> array('lo'=> 90000, 'hi'=> 96199),
    'CO'=> array('lo'=> 80000, 'hi'=> 81699),
    'CT'=> array('lo'=> 6000, 'hi'=> 6999),
    'DC'=> array('lo'=> 20000, 'hi'=> 20599),
    'DE'=> array('lo'=> 19700, 'hi'=> 19999),
    'FL'=> array('lo'=> 32000, 'hi'=> 34999),
    'GA'=> array('lo'=> 30000, 'hi'=> 39999),
    'HI'=> array('lo'=> 96700, 'hi'=> 96899),
    'IA'=> array('lo'=> 50000, 'hi'=> 52899),
    'ID'=> array('lo'=> 83200, 'hi'=> 83899),
    'IL'=> array('lo'=> 60000, 'hi'=> 62999),
    'IN'=> array('lo'=> 46000, 'hi'=> 47999),
    'KS'=> array('lo'=> 66000, 'hi'=> 67999),
    'KY'=> array('lo'=> 40000, 'hi'=> 47199),
    'LA'=> array('lo'=> 70000, 'hi'=> 71499),
    'MA'=> array('lo'=> 1000, 'hi'=> 5599),
    'MD'=> array('lo'=> 20600, 'hi'=> 21999),
    'ME'=> array('lo'=> 3900, 'hi'=> 4999),
    'MI'=> array('lo'=> 48000, 'hi'=> 49999),
    'MN'=> array('lo'=> 55000, 'hi'=> 56699),
    'MO'=> array('lo'=> 63000, 'hi'=> 65899),
    'MS'=> array('lo'=> 38600, 'hi'=> 39799),
    'MT'=> array('lo'=> 59000, 'hi'=> 59999),
    'NC'=> array('lo'=> 27000, 'hi'=> 28999),
    'ND'=> array('lo'=> 56700, 'hi'=> 58899),
    'NE'=> array('lo'=> 68000, 'hi'=> 69399),
    'NH'=> array('lo'=> 3000, 'hi'=> 3899),
    'NJ'=> array('lo'=> 7000, 'hi'=> 8999),
    'NM'=> array('lo'=> 87000, 'hi'=> 88499),
    'NV'=> array('lo'=> 88900, 'hi'=> 89899),
    'NY'=> array('lo'=> 500, 'hi'=> 14999),
    'OH'=> array('lo'=> 43000, 'hi'=> 45999),
    'OK'=> array('lo'=> 73000, 'hi'=> 74999),
    'OR'=> array('lo'=> 97000, 'hi'=> 97999),
    'PA'=> array('lo'=> 15000, 'hi'=> 19699),
    'RI'=> array('lo'=> 2800, 'hi'=> 2999),
    'SC'=> array('lo'=> 29000, 'hi'=> 29999),
    'SD'=> array('lo'=> 57000, 'hi'=> 57799),
    'TN'=> array('lo'=> 37000, 'hi'=> 38599),
    'TX'=> array('lo'=> 73300, 'hi'=> 88599),
    'UT'=> array('lo'=> 84000, 'hi'=> 84799),
    'VA'=> array('lo'=> 20100, 'hi'=> 24699),
    'VT'=> array('lo'=> 5000, 'hi'=> 5999),
    'WA'=> array('lo'=> 98000, 'hi'=> 99499),
    'WI'=> array('lo'=> 53000, 'hi'=> 54999),
    'WV'=> array('lo'=> 24700, 'hi'=> 26899),
    'WY'=> array('lo'=> 82000, 'hi'=> 83199),    
    ];

    if(!$state){
        return $rangesSimple;
    } else {
        return $rangesSimple[$state];
    }
    
}

function getZipRange($zip){
    $neighbors = getNeighborStates($zip);
    $rangesSimple = getSimpleRange();
    $fullZipRange = [];
    $zipRange = [];

    foreach ($neighbors as $i=>$val){
        array_push($fullZipRange,$rangesSimple[$neighbors[$i]]['lo']);
        array_push($fullZipRange,$rangesSimple[$neighbors[$i]]['hi']);
    }

    sort($fullZipRange,SORT_NUMERIC);
    $minZip = $fullZipRange[0];
    $maxZip = $fullZipRange[count($fullZipRange)-1];

    array_push($zipRange,$minZip);
    array_push($zipRange,$maxZip);

    return $zipRange;
}

function getNearbyZips($zip, $radius, $showDist=false) {
    $zipRange = getZipRange($zip);
    $min = $zipRange[0];
    $max = $zipRange[1];
    $baseLat = getCoord($zip,'lat');
    $baseLon = getCoord($zip,'lon');
    $allZips = allZips();
    $results = [];

    if($showDist===false){
        foreach ($allZips as $i=>$val) {
            if($i>=$min && $i<=$max){
                if(pythag_distance($baseLat,$allZips[$i]['lat'],$baseLon,$allZips[$i]['lon'],'mi')<=$radius){
                    array_push($results,$i);
                }
            }        
        }
    } else if ($showDist===true){
        foreach ($allZips as $i=>$val) {
            if($i>=$min && $i<=$max){
                if(pythag_distance($baseLat,$allZips[$i]['lat'],$baseLon,$allZips[$i]['lon'],'mi')<=$radius){
                    $results[$i] = pythag_distance($baseLat,$allZips[$i]['lat'],$baseLon,$allZips[$i]['lon'],'mi');
                }
            }        
        }
        asort($results);
    }    
    return $results;
}

function getAllNearbyZips($radius) {

    foreach ($allZips as $curZip) {
        $baseLat = $allZips[$curZip]['lat'];
        $baseLon = $allZips[$curZip]['lon'];

        $zipRange = getZipRange($curZip);
        $min = $zipRange[0];
        $max = $zipRange[1];

        $nearbyZips = '';                
        
        foreach ($allZips as $compareZip) {
            if($compareZip>=$min && $compareZip<=$max){                
                if(pythag_distance($baseLat,$allZips[$compareZip]['lat'],$baseLon,$allZips[$compareZip]['lon'],'mi')<=$radius){
                    $nearbyZips+=$compareZip+',';                    
                }
            }        
        }
    }
}

function getStateZips($zip){
    $state = getState($zip);
    $stateZips = [];
    $allZips = allZips(false);
    $simpleRange = getSimpleRange($state);
    foreach($allZips as $zip){
        if($zip>=$simpleRange['lo'] && $zip<=$simpleRange['hi']){
            if(getState($zip)===$state){
                array_push($stateZips,$zip);
            } 
        }               
    }
    return $stateZips;
}