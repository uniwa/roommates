<?php
    /////////////////////////////////////
    // TODO filter image element (id etc)
    /////////////////////////////////////

    $serialized = '<houses>'.$xml->serialize($houses, array('format' => 'tags')).'</houses>';
    $output = new Xml($serialized);

    // for each <house> element generate id attributes
    // the id value is equal to the <id> element
    foreach ($output->children[0]->children as $house) {
        $house->attributes['id'] = $house->children[0]->children[0]->value;
        // id element
        unset($house->children[0]->children[0]);
        // address element
        unset($house->children[1]->children[0]);
    }

    // convert Xml object to string and remove
    // the unecessary <id /> elements
    $output = $output->compose();
    $output = str_replace('<id />', '', $output);

    echo $output;
?>

