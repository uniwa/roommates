<?php

class House extends AppModel {
    var $name = 'House';
    var $belongsTo = array('HouseType', 'Floor', 'HeatingType');

    var $validate = array(

        'address' => array(
            'maxsize' => array(
                'rule' => array('maxLength', 50),
                'message' => 'Η διεύθυνση μπορεί να περιέχει μέχρι 50 χαρακτήρες.'
            ),
            'not_empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Η διεύθυνση δεν μπορεί να είναι κενή.'
            ),
            'valid' => array(
                'rule' => '/[a-zA-Zα-ωΑ-Ω0-9ΆάΈέΎύΊίΌόΏώϊϋΐΰ,. ]+$/',
                'message' => 'Η διεύθυνση περιέχει έναν μη έγκυρο χαρακτήρα.'
            )
        ),

        'area' => array (
            'rule' => '/^[1-9]\d{0,2}$/',
            'message' => 'Εισάγετε έναν θετικό ακέραιο αριθμό, έως 999.'
        ),

        'bedroom_num' => array(
            'rule' => '/^[1-9]\d{0,1}$/',
            'message' => 'Εισάγετε έναν θετικό ακέραιο αριθμό, έως 99.'
        ),

        'price' => array(
            'rule' => '/^[1-9]\d{0,3}$/',
            'message' => 'Εισάγετε έναν θετικό ακέραιο αριθμό, έως 9999.'
        ),

        'postal_code' => array(
            'rule' => '/^\d{5}$/',
            'message' => 'Εισάγετε σωστό ταχυδρομικό κώδικα.',
            'required' => false,
            'allowEmpty' => true
        ),

        'bathroom_num' => array(
            'rule' => '/^[1-9]$/',
            'message' => 'Εισάγετε έναν θετικό ακέραιο αριθμό, έως 9.',
            'required' => false,
            'allowEmpty' => true
        ),

        'rent_period' => array(
            'rule' => '/^[1-9]\d{0,2}$/',
            'message' => 'Εισάγετε έναν θετικό ακέραιο αριθμό, έως 999.',
            'required' => false,
            'allowEmpty' => true
        ),

        'construction_year' => array(
            'rule' => '/^\d{4}$/',
            'message' => 'Εισάγετε έναν τετραψήφιο αριθμό.',
            'required' => false,
            'allowEmpty' => true
        ),

        'solar_heater' => array(
            'rule' => '/^[0-1]$/',
            'message' => 'Υπήρξε κάποιο σφάλμα.',
            'required' => false,
            'allowEmpty' => true
        ),

        'furnitured' => array(
            'rule' => '/^[0-1]$/',
            'message' => 'Υπήρξε κάποιο σφάλμα.',
            'required' => false,
            'allowEmpty' => true
        ),

        'aircondition' => array(
            'rule' => '/^[0-1]$/',
            'message' => 'Υπήρξε κάποιο σφάλμα.',
            'required' => false,
            'allowEmpty' => true
        ),

        'garden' => array(
            'rule' => '/^[0-1]$/',
            'message' => 'Υπήρξε κάποιο σφάλμα.',
            'required' => false,
            'allowEmpty' => true
        ),

        'parking' => array(
            'rule' => '/^[0-1]$/',
            'message' => 'Υπήρξε κάποιο σφάλμα.',
            'required' => false,
            'allowEmpty' => true
        ),

        'shared_pay' => array(
            'rule' => '/^[0-1]$/',
            'message' => 'Υπήρξε κάποιο σφάλμα.',
            'required' => false,
            'allowEmpty' => true
        ),

        'security_doors' => array(
            'rule' => '/^[0-1]$/',
            'message' => 'Υπήρξε κάποιο σφάλμα.',
            'required' => false,
            'allowEmpty' => true
        ),

        'disability_facilities' => array(
            'rule' => '/^[0-1]$/',
            'message' => 'Υπήρξε κάποιο σφάλμα.',
            'required' => false,
            'allowEmpty' => true
        ),

        'storeroom' => array(
            'rule' => '/^[0-1]$/',
            'message' => 'Υπήρξε κάποιο σφάλμα.',
            'required' => false,
            'allowEmpty' => true
        )
    );
}

?>
