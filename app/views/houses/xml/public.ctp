<?php
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
        // created element
        // replace the space with 'T'
        $house->children[20]->children[0]->value =
            str_replace(' ', 'T', $house->children[20]->children[0]->value);
        // modified element
        // replace the space with 'T'
        $house->children[21]->children[0]->value =
            str_replace(' ', 'T', $house->children[21]->children[0]->value);
    }

    // convert Xml object to string and remove
    // the unecessary <id /> elements
    $output = $output->compose();
    $output = str_replace('<id />', '', $output);
    $output = str_replace(
            '<houses>',
            '<houses xmlns="http://www.roommates.teiath.gr/schemas/house">',
            $output);
    echo $output;
?>
