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
            'size' => array(
                'rule' => array('between', 2, 3),
                'message' => 'Το εμβαδό πρέπει να είναι διψήφιος ή τριψήφιος αριθμός.'
            ),
            'integer' => array(
                'rule' => '/^\d+$/',
                'message' => 'Εισάγετε έναν θετικό ακέραιο αριθμό.'
            )
        ),

        'bedroom_num' => array(
            'size' => array(
                'rule' => array('between', 1, 2),
                'message' => 'Ο αριθμός δωματίων πρέπει να είναι το πολύ διψήφιος αριθμός.'
            ),
            'integer' => array(
                'rule' => '/^\d+$/',
                'message' => 'Εισάγετε έναν θετικό ακέραιο αριθμό.'
            )
        ),

        'price' => array(
            'size' => array(
                'rule' => array('maxLength', 4),
                'message' => 'Η τιμή μπορεί να είναι το πολύ τετραψήφιος αριθμός.'
            ),
            'integer' => array(
                'rule' => '/^\d+$/',
                'message' => 'Εισάγετε έναν θετικό ακέραιο αριθμό.'
            )
        ),

        'postal_code' => array(
            'size' => array(
                'rule' => array('between', 5, 5),
                'message' => 'Εισάγετε σωστό ταχυδρομικό κώδικα.',
                'required' => false,
                'allowEmpty' => true,
            ),
            'integer' => array(
                'rule' => '/^\d+$/',
                'message' => 'Εισάγετε σωστό ταχυδρομικό κώδικα.'
            )
        ),

        'bathroom_num' => array(
            'size' => array(
                'rule' => array('maxLength', 2),
                'message' => 'Ο αριθμός των μπάνιων μπορεί να είναι το πολύ διψήφιος αριθμός.',
                'required' => false,
                'allowEmpty' => true
            ),
            'integer' => array(
                'rule' => '/^\d+$/',
                'message' => 'Εισάγετε έναν θετικό ακέραιο αριθμό.'
            )
        ),

        'rent_period' => array(
            'size' => array(
                'rule' => array('maxLength', 3),
                'message' => 'Η περίοδος ενοικίασης μπορεί να είναι το πολύ τριψήφιος αριθμός.',
                'required' => false,
                'allowEmpty' => true
            ),
            'integer' => array(
                'rule' => '/^\d+$/',
                'message' => 'Εισάγετε έναν θετικό ακέραιο αριθμό.'
            )
        ),

        'construction_year' => array(
            'rule' => '/^\d{4}$/',
            'message' => 'Εισάγετε έναν τετραψήφιο αριθμό.',
            'required' => false,
            'allowEmpty' => true
        )
    );
}

?>
