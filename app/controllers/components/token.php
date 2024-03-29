<?php
class TokenComponent extends Object {
    function generate($entropy = "") {
        /* return a 40 chars unique id */
        return sha1(uniqid($entropy, true));
    }

    function to_id($token) {
        /* return _profile_ id associated with given token */
        $profile =& ClassRegistry::init('Profile');
        $id = $profile->find('first', array(
            'fields' => array('id'),
            'conditions' => array('token' => $token)
        ));
        if (isset($id["Profile"]["id"])) {
            return $id["Profile"]["id"];
        }
        return NULL;
    }
}
?>
