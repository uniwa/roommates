<?php
class User extends AppModel{

    var $name = 'User';
    var $hasMany = array("House");
    var $hasOne = array('Profile', 'RealEstate');

    var $validate = array(
        'username' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'Το πεδίο αυτό δεν μπορεί να είναι κενό'
            ),
            'alphanumeric' => array(
                'rule' => 'alphanumeric',
                'message' => 'Επιτρέπονται μόνο αλφαριθμητικά',
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'Αυτό το όνομα χρήστη χρησιμοποιείται ήδη'
            )
        ),

        'password' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'required' => true,
//                 'message' => 'Το πεδίο αυτό δεν μπορεί να είναι κενό.'
            ),
            'length' => array (
                'rule' => array('minLength', 8),
                'allowEmpty' => true,
//                 'message' => 'Ο κωδικός πρέπει να είναι μεταξύ 8 και 16 χαρακτήρων.'
            ),
            'alphanumeric' => array(
                'rule' => '/^[\d\w!@#\$%&\*\^\+\?-_.,]+$/',
                'allowEmpty' => true,
//                 'message' => 'Υπάρχει κάποιος μη αποδεκτός χαρακτήρας'
            )
        ),

        'password_confirm' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Το πεδίο αυτό δεν μπορεί να είναι κενό.'
            ),
            'length' => array (
                'rule' => array('between', 8, 16),
                'allowEmpty' => true,
                'message' => 'Ο κωδικός πρέπει να είναι μεταξύ 8 και 16 χαρακτήρων.'
            ),
            'identical_passwd' => array(
                'rule' => array('identical_password', 'password'),
                'allowEmpty' => true,
                'message' => 'Οι 2 κωδικοί δεν ταιριάζουν'
            ),
            'alphanumeric' => array(
                'rule' => '/^[\d\w!@#\$%&\*\^\+\?-_.,]+$/',
                'allowEmpty' => true,
                'message' => 'Υπάρχει κάποιος μη αποδεκτός χαρακτήρας'
            )
        )
    );

    function identical_password($check, $passwd)
    {
        $hashed_pass = Security::hash(Configure::read('Security.salt') . $check["password_confirm"]);
        // We must manually hash the second piece in the same way the AuthComponent would
        // if they match, return true!
        if ($this->data[$this->name][$passwd] == $hashed_pass) {
            return true;
        }

        // hashed passwords did NOT match
        return false;
    }
}
?>
