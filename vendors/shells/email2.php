<?php
class EmailShell extends Shell{

    var $uses = array('House', 'Preference', 'User', 'Profile');
    function main(){
        
        //get houses created or modified today
        $today = date('Y-m-d', strtotime("+1 day")); //because daysAsSql returns yesterday, use strtotime
        $from = $today; 
        $to = $today;
        App::import('Helper', 'Time');
        $time = new TimeHelper();
        //$conditions_created = $time->daysAsSql($from, $to, "created", true);
        //$conditions_modified = $time->daysAsSql($from, $to, "modified", true);
        //$conditions = '(' . $conditions_created . ') OR (' .$conditions_modified . ')'; 
        //echo($conditions);die();

        $options['fields'] = array('User.*', 'Profile.*', 'Preference.*', 'House.*');
        //$options['conditions'] = array($conditions);
        /*$options['conditions'] = array( 'OR' => array('House.created' => $conditions_created,
                                                      'House.modified' => $conditions_modified));*/

        //$options['conditions'] = array('House.modified BETWEEN ? AND ?' => array(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')));
        $options['joins'] = array(
                                array('table' => 'profiles',
                                      'alias' => 'Profile',
                                      'type' => 'left',
                                      'conditions' => array('Profile.user_id = User.id')
                                ),
                                array('table' => 'preferences',
                                      'alias' => 'Preference',
                                      'type' => 'left', 
                                      'conditions' => array('Profile.preference_id = Preference.id')
                                ),
                                array('table' => 'houses',
                                      'alias' => 'House',
                                      'type' => 'inner',
                                      'conditions' => array(
                                                                array(
                                                                    'OR' => array(
                                                                                'House.modified BETWEEN ? AND ?' => array(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')),
                                                                                'House.created BETWEEN ? AND ?' => array(date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59'))
                                                                    )
                                                                ),

                                                                array(
                                                                    'OR' => array(
                                                                        array(
                                                                            'AND' => array(
                                                                                    'NOT' => array('Preference.area_min' => null, 'Preference.area_max' => null),
                                                                                    //'NOT' => array('Preference.area_max' => null),
                                                                                    array('House.area BETWEEN ? AND ?' => array('Preference.area_min', 'Preference.area_max'))
                                                                            )
                                                                        ),
                                                                        array(
                                                                            'AND' => array( 
                                                                                        array('Preference.area_max' => null),
                                                                                        'NOT' => array(
                                                                                                array('Preference.area_min' => null)
                                                                                        ),
                                                                                        array('House.area >' => ' Preference.area_min')
                                                                            )
                                                                        ),
                                                                        array('AND' => array( 
                                                                                   array('Preference.area_min' => null),
                                                                                    'NOT' => array(
                                                                                                array('Preference.area_max' => null)
                                                                                    ),
                                                                                    array('House.area <' => ' Preference.area_max')
                                                                            )
                                                                        ),
                                                                        array('AND' => array(
                                                                            array('Preference.area_min' => null),
                                                                            array('Preference.area_max' => null)
                                                                            )
                                                                        )
                                                                    )
                                                                )
                                                           )
                                 )
        );

        //pr($options);die();
        $this->User->recursive = -1;
        $profiles = $this->User->find('all', $options);
        pr($profiles);die();

        //pr($options);die();
        //$houses = $this->House->find('all', $options);
        //pr($houses);die();

        //$houses = $this->House->find('all', array('conditions' => array($conditions)));
        //pr($houses);die();  //House, HouseType, Floor, HeatingType, Municipality, User, Image 

        //get users whose preferences match with today's created or modified houses data
        //$users = $this->Profile->find('all', array('conditions' => array(   )));
        //pr($users);die(); //User, Preference, Profile 


        /***** debugging ******/
        /*if($houses){
            foreach($houses as $house){
                $this->out('Created today!  ' . $house['House']['address'] . "\n");
            }
        }
        else $this->out('no new houses today');*/
        /***** end-debugging *****/
    }
}
?>
