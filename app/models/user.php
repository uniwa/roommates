<?php
class User extends AppModel{

    var $name = 'User';
    var $hasMany = array("House");
    var $hasOne = array('Profile', 'RealEstate');

    var $validate = array(
        'username' => array(
            'rule' => 'alphanumeric',
            'message' => 'Please enter a valid username',
            'required' => true
        ),
        'password' => array(
            'rule' => 'alphanumeric',
            'required' => true,
            'allowEmpty' => false
        ),
        'password_confirm' => array(
            'identical_passwd' => array(
                'rule' => array('identical_password', 'password'),
                'required' => true,
                'allowEmpty' => false,
                'message' => 'Οι 2 κωδικοί δεν ταιριάζουν'
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

    // Check if the username already exists by doing SELECT COUNT(*) FROM users WHERE username = 'your_username'
    function beforeValidate()
    {
        if( isset($this->data["User"]["username"]) )
        {
            $conditions = array('User.username' => $this->data['User']['username'] );
            if( $this->find( 'count', array('conditions' => $conditions) ) > 0 )
            {
                // If any rows are found, send an error and call it 'username_unique'
                // In our view, we can check for this by doing $form->error('username_unique','Not Unique Username!!!')
                //   As specified in the view code I placed above
                $this->invalidate('username_unique');
                return false;
            }
        }
        return true;
    }
}
?>
