<?php
class EmailShell extends Shell{

    var $uses = array('House', 'Preference', 'User', 'Profile');
    function main(){
        
        $today = date('Y-m-d', strtotime("+1 day")); //because daysAsSql returns yesterday, use strtotime
        $from = $today; 
        $to = $today;
        App::import('Helper', 'Time');
        $time = new TimeHelper();
        $conditions_created = $time->daysAsSql($from, $to, "created", true);
        $conditions_modified = $time->daysAsSql($from, $to, "modified", true);
        $conditions = '(' . $conditions_created . ') OR (' .$conditions_modified . ')'; 
        //echo($conditions);die();

        
        //get houses created or modified today
        $houses = $this->House->find('all', array('conditions' => $conditions));
        //pr($houses);die();  //House, HouseType, Floor, HeatingType, Municipality, User, Image 
        if (isset($houses)) count($houses);


        //get (only) users data and preferences 
        $users = $this->Profile->find('all', array('conditions' => array( 'User.role' => 'user' )));
        //pr($users);die(); //User, Preference, Profile 
        if (isset($users)) count($users);

        $email_users = array();


        for ($i=0; $i<=$users; $i++){
            for($j=0; $j<=$houses; $j++){
                
                if(    ( $this->compare_min($houses['House']['area'], $users['Preference']['area_min']))
                    && ( $this->compare_max($houses['House']['area'], $users['Preference']['area_max']))
                    && ( $this->compare_min($houses['House']['bedroom_num'], $users['Preference']['bedroom_num_min']))
                    && ( $this->compare_max($houses['House']['price'], $users['Preference']['price_max']))
                    && ( $this->compare_min($houses['House']['floor_id'], $users['Preference']['floor_id_min']) )
            
                    && ( $this->compare_pref_min($houses['House']['bathroom_num'], $users['Preference']['bathroom_num_min'])) //not comp
                    && ( $this->compare_pref_min($houses['House']['construction_year'], $users['Preference']['construction_year_min']) ) //not comp
                    && ( $this->compare_pref_min($houses['House']['rent_period'], $users['Preference']['rent_period_min']) ) //not comp
                    && ( $this->compare_checkbox_null($houses['House']['solar_heater'], $users['Preference']['pref_solar_heater']) ) //not comp
                    && ( $this->compare_checkbox_null($houses['House']['aircondition'], $users['Preference']['pref_aircondition']) )
                    && ( $this->compare_checkbox_null($houses['House']['garden'], $users['Preference']['pref_garden']) )
                    && ( $this->compare_checkbox_null($houses['House']['parking'], $users['Preference']['pref_parking']) )
                    && ( $this->compare_checkbox_null($houses['House']['security_doors'], $users['Preference']['pref_security_doors']) )
                    && ( $this->compare_checkbox_null($houses['House']['storeroom'], $users['Preference']['pref_storeroom']) )
                    
                    && ( $this->compare_equal($houses['House']['house_type_id'], $users['Preference']['pref_house_type_id']))
                    && ( $this->compare_equal($houses['House']['heating_type_id'], $users['Preference']['pref_heating_type_id']))
                    
                    && ( $this->compare_equal_null($houses['House']['municipality_id'], $users['Preference']['pref_municipality'])) //not OK
                    && ( $this->shared_pay($houses['House']['shared_pay'], $users['Preference']['pref_shared_pay']) )
                    
                    && ( $this->compare_date_min($houses['House']['availability_date'], $users['Preference']['availability_date_min']) )
                    
                    && ( $this->compare_checkbox($houses['House']['furnitured'], $users['Preference']['pref_furnitured']) )
                    && ( $this->compare_checkbox($houses['House']['disability_facilities'], $users['Preference']['pref_disability_facilities']) )

                    && ( $this->has_photo($houses['House']['default_image_id'], $users['Preference']['pref_has_photo']))
                    
                )
                {
                    $email_users[$users['Profile']['email']] = $houses['House']['id'];
                }
            }
        }

        var_dump($email_users);
    }

        private function compare_date_min($attr, $pref){
            if( strtotime($attr) <= strtotime($pref)){
                return true;    
            }else{
                return false;    
            }
        }

        private function compare_checkbox_null($attr, $pref){
            if (isset($attr)){ 
                return true;
            }else if ( !isset($attr) && !isset($pref) ){ 
                return true;
            }else if ( !isset($attr) && isset($pref) ){
                return false;
            }
        }

        private function has_photo($attr, $pref){
            if (!isset($attr) && ($pref)){
                return false;
            }else{
                return true;    
            }
        }


        private function compare_checkbox($attr, $pref){
            if (!isset($attr) && ($pref == 1) ){ 
                return false;
            }else{
                return false;
            }
        }

        private function compare_pref_min($attr, $pref){
            if( ( isset($attr) && !isset($pref) ) || ( !isset($attr) && !isset($pref) ) ){
                return true;
            }else if ( isset($attr) && isset($pref) ){
                if ($attr >= $pref) return true;
            }else if ( !isset($attr) && isset($pref) ){
                return false;
            }
        }

        private function compare_max($attr, $pref){
            if( isset($pref) && ($attr <= $pref) ){
                return true;
            }else{
                return false;
            }
        }

        private function compare_min($attr, $pref){
            if( isset($pref) && ($attr >= $pref) ){
                return true;
            }else{
                return false;
            }
        }

        private function compare_equal($attr, $pref){
            if( $pref == 0  || $attr == $pref ){
                return true;
            }else{
                return false;
            }
        }

        private function compare_equal_null($attr, $pref){
            if( !isset($pref)  || ( isset($pref) && $attr == $pref ) ){
                return true;
            }else{
                return false;
            }
        }

        private function shared_pay($attr, $pref){
            if ( isset($pref) && ($attr == 1) ){
                return true;
            }else{
                return false;    
            }
        }
}
?>
