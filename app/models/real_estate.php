<?php

class RealEstate extends AppModel {

    var $name = 'RealEstate';

    var $belongsTo = array('User', 'Municipality');

    var $validate = array(

        'firstname' => array(
            'not_empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Παρακαλώ εισάγετε ένα όνομα.',
                'required' => true
            ),
            'maxsize' => array(
                'rule' => array('maxLength', 45),
                'allowEmpty' => true,
                'message' => 'Το όνομα μπορεί να περιέχει μέχρι 45 χαρακτήρες.'
            ),
            'valid' => array(
                'rule' => '/^[\wαβγδεζηθικλμνξοπρστυφχψωΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΆάΈέΎΉήύΊίΌόΏώϊϋΐΰς]+$/',
                'allowEmpty' => true,
                'message' => 'Το όνομα περιέχει έναν μη έγκυρο χαρακτήρα.'
            )
        ),

        'lastname' => array(
            'not_empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Παρακαλώ εισάγετε ένα επίθετο.',
                'required' => true
            ),
            'maxsize' => array(
                'rule' => array('maxLength', 45),
                'allowEmpty' => true,
                'message' => 'Το επίθετο μπορεί να περιέχει μέχρι 45 χαρακτήρες.'
            ),
            'valid' => array(
                'rule' => '/^[\wαβγδεζηθικλμνξοπρστυφχψωΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΆάΈέΎΉήύΊίΌόΏώϊϋΐΰς]+$/',
                'allowEmpty' => true,
                'message' => 'Το επίθετο περιέχει έναν μη έγκυρο χαρακτήρα.'
            )
        ),

        'company_name' => array(
            'not_empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Παρακαλώ εισάγετε την επωνυμία.',
                'required' => true
            ),
            'maxsize' => array(
                'rule' => array('maxLength', 100),
                'allowEmpty' => true,
                'message' => 'Η επωνυμία μπορεί να περιέχει μέχρι 100 χαρακτήρες.'
            ),
            'valid' => array(
                'rule' => '/^[\w\dαβγδεζηθικλμνξοπρστυφχψωΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΆάΈέΎΉήύΊίΌόΏώϊϋΐΰς,. &]+$/',
                'allowEmpty' => true,
                'message' => 'Η επωνυμία περιέχει έναν μη έγκυρο χαρακτήρα.'
            )
        ),

        'email' => array(
            'not_empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Παρακαλώ εισάγετε ηλεκτρονική διεύθυνση.',
                'required' => true
            ),
            'valid' => array (
                'rule' => array('email', true),
                'allowEmpty' => true,
                'message' => 'Παρακαλώ εισάγετε έγκυρη ηλεκτρονική διεύθυνση.'
            )
        ),

        'phone' => array(
            'not_empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Παρακαλώ εισάγετε ένα τηλέφωνο επικοινωνίας.',
                'required' => true
            ),
            'size' => array(
                'rule' => array('between', 10, 10),
                'message' => 'Ο αριθμός τηλεφώνου πρέπει να περιέχει 10 ψηφία.',
                'allowEmpty' => true
            ),
            'valid' => array(
                'rule' => '/^\d+$/',
                'message' => 'Ο αριθμός μπορεί να περιέχει μόνο ψηφία.',
                'allowEmpty' => true
            )
        ),

        'fax' => array(
            'size' => array(
                'rule' => array('between', 10, 10),
                'message' => 'Ο αριθμός fax πρέπει να περιέχει 10 ψηφία.',
                'allowEmpty' => true,
                'required' => false
            ),
            'valid' => array(
                'rule' => '/^\d+$/',
                'message' => 'Ο αριθμός περιέχει έναν μη έγκυρο χαρακτήρα.',
                'allowEmpty' => true,
                'required' => false
            )
        ),

        'afm' => array(
            'not_empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Παρακαλώ εισάγετε το ΑΦΜ σας.',
                'required' => true
            ),
            'size' => array(
                'rule' => array('between', 9, 9),
                'allowEmpty' => true,
                'message' => 'Το ΑΦΜ πρέπει να περιέχει ακριβώς 9 ψηφία.'
            ),
            'valid' => array(
                'rule' => '/^\d+$/',
                'message' => 'Το ΑΦΜ πρέπει να περιέχει μόνο ψηφία',
                'allowEmpty' => true
            )
        ),

        'doy' => array(
            'size' => array(
                'rule' => array('maxLength', 45),
                'message' => 'Το όνομα της ΔΟΥ μπορεί να είναι μέχρι 45 χαρακτήρες.',
                'allowEmpty' => true,
                'required' => false
            ),
            'valid' => array(
                'rule' => "/^[\dαβγδεζηθικλμνξοπρστυφχψωΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΆάΈέΎΉήύΊίΌόΏώϊϋΐΰς,. '`]+$/",
                'message' => 'Το όνομα της ΔΟΥ περιέχει έναν μη έγκυρο χαρακτήρα.',
                'allowEmpty' => true,
                'required' => false
            )
        ),

        'address' => array(
            'size' => array(
                'rule' => array('maxLength', 45),
                'message' => 'Η διεύθυνση μπορεί να περιέχει μέχρι 45 χαρακτήρες.',
                'allowEmpty' => true,
                'required' => false
            ),
            'valid' => array(
                'rule' => '/^[\w\dαβγδεζηθικλμνξοπρστυφχψωΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΆάΈέΎΉήύΊίΌόΏώϊϋΐΰς,. &]+$/',
                'message' => 'Η διεύθυνση περιέχει έναν μη έγκυρο χαρακτήρα.',
                'allowEmpty' => true,
                'required' => false
            )
        ),

        'postal_code' => array(
            'rule' => '/^\d{5}$/',
            'message' => 'Εισάγετε σωστό ταχυδρομικό κώδικα.',
            'required' => false,
            'allowEmpty' => true
        ),

        'municipality_id' => array(
            'rule' => array('range', 0, 67), /* 66 municipalities in db - ids between 1 and 66 */
            'message' => 'Παρουσιάστηκε κάποιο σφάλμα.',
            'allowEmpty' => true,
            'required' => false
        )
    );


    function beforeValidate() {

        if ($this->data['RealEstate']['company_name'] == null) {
            $this->data['RealEstate']['company_name'] =
                $this->data['RealEstate']['lastname'].' '.
                $this->data['RealEstate']['firstname'];
        }

        return true;
    }
}

?>
