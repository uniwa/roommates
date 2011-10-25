<?php

class House extends AppModel {
    var $name = 'House';
    var $belongsTo = array('HouseType', 'Floor', 'HeatingType', 'Municipality', 'User');

    var $hasMany = array ('Image');

	var $virtualFields = array(
		'free_places' => "House.total_places - House.currently_hosting"
	);

    var $validate = array(

        'house_type_id' => array(
            'not_empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Παρακαλώ επιλέξτε έναν τύπο.'
            ),
            'number' => array(
                'rule' => '/^[1-5]$/',
                'message' => 'Παρουσιάστηκε κάποιο σφάλμα.',
                'allowEmpty' => true
            )
        ),

        'municipality_id' => array(
            'not_empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Παρακαλώ επιλέξτε δήμο.',
            ),
            'number' => array(
                'rule' => array('range', 0, 67), /* 66 municipalities in db - ids between 1 and 66 */
                'message' => 'Παρουσιάστικε κάποιο σφάλμα.',
                'allowEmpty' => true
            )
        ),

        'floor_id' => array(
            'not_empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Παρακαλώ επιλέξτε όροφο.'
            ),
            'number' => array(
                'rule' => '/^[1-9][0-3]{0,1}$/',
                'message' => 'Παρουσιάστηκε κάποιο σφάλμα.',
                'allowEmpty' => true
            )
        ),

        'heating_type_id' => array(
            'not_empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Παρακαλώ επιλέξτε είδος θέρμανσης.'
            ),
            'number' => array(
                'rule' => '/^[1-3]$/',
                'message' => 'Παρουσιάστηκε κάποιο σφάλμα.',
                'allowEmpty' => true
            )
        ),

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
                'rule' => '/^[\w\dαβγδεζηθικλμνξοπρστυφχψωΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΆάΈέΎΉήύΊίΌόΏώϊϋΐΰς,. &]+$/',
                'message' => 'Η διεύθυνση περιέχει έναν μη έγκυρο χαρακτήρα.',
                'allowEmpty' => true
            )
        ),

        'availability_date' => array(
            'non_empty' => array(
                'rule' => 'notEmpty',
                'message' => 'Παρακαλώ εισάγετε μια ημερομηνία.'
            ),
            'valid_date' => array(
                'rule' => 'date',
                'message' => 'Παρουσιάστηκε κάποιο σφάλμα.',
                'allowEmpty' => true
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
            'message' => 'Εισάγετε έναν θετικό ακέραιο αριθμό, χωρίς σημεία στίξης.'
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
        ),

	'currently_hosting' => array(
	    'rule' => '/^[1-9]{1}$/i',
	    'message' =>'Παρακαλώ εισάγετε τον τρέχοντα αριθμό κατοίκων της οικίας'
	),


	'total_places' => array(
	    'rule' => '/^[1-9]{1}$/i',
	    'message' =>'Παρακαλώ εισάγετε τη συνολική διαθεσιμότητα θέσεων στην οικία'
	)
    );
}

?>
