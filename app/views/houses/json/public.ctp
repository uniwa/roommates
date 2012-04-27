<?php
foreach ($houses as $key => $house) {
    $houses[$key]['house'] = $house['House'];
    unset($houses[$key]['House']);

    $houses[$key]['house']['municipality'] = $house['Municipality'];
    unset($houses[$key]['Municipality']);

    $houses[$key]['house']['floor'] = $house['Floor'];
    unset($houses[$key]['Floor']);

    $houses[$key]['house']['house_type'] = $house['HouseType'];
    unset($houses[$key]['HouseType']);

    $houses[$key]['house']['heating_type'] = $house['HeatingType'];
    unset($houses[$key]['HeatingType']);

    $houses[$key]['house']['image'] = $house['Image'];
    unset($houses[$key]['Image']);
}

echo $javascript->object($houses);
