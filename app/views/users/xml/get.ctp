<?php
    $serialized = '<users>'.$xml->serialize($users, array('format' => 'tags')).'</users>';
    $output = new Xml($serialized);

    // set the element "id" as attribute for each
    // "student" or "real_estate" or "private_landowner"
    foreach ($output->children[0]->children as $user) {
        $user->attributes['id'] = $user->children[0]->children[0]->value;
        unset($user->children[0]->children[0]);
    }

    $output = $output->compose();
    $output = str_replace('<id />', '', $output);
    echo $output;
?>
