<?php
class TokenComponent extends Object {
    function generate($entropy = "") {
        /* return a 40 chars unique id */
        return sha1(uniqid($entropy, true));
    }
}
?>
