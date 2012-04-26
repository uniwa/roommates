<?php
foreach ($houses as $key => $house) {
    $houses[$key]['house'] = $house['House'];
    unset($houses[$key]['House']);

    $houses[$key]['municipality'] = $house['Municipality'];
    unset($houses[$key]['Municipality']);

    $houses[$key]['floor'] = $house['Floor'];
    unset($houses[$key]['Floor']);

    $houses[$key]['house_type'] = $house['HouseType'];
    unset($houses[$key]['HouseType']);

    $houses[$key]['heating_type'] = $house['HeatingType'];
    unset($houses[$key]['HeatingType']);

    $houses[$key]['image'] = $house['Image'];
    unset($houses[$key]['Image']);
}

echo $javascript->object($houses);
