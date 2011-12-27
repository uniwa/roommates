<?php
    $users = array('users' => $users);
    $serialized = $xml->serialize($users, array(
        'format' => 'tags',
        'namespaces' => array(
            'tns' => 'http://www.roommates.teiath.gr/schemas/user' )));
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
