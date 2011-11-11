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
        //if (isset($houses)) count($houses);


        //get (only) users data and preferences 
        $users = $this->Profile->find('all', array('conditions' => array( 'User.role' => 'user' )));
        //pr($users);die(); //User, Preference, Profile 
        //if (isset($users)) count($users);

        $email_users = array();


        for ($i=0; $i<=count($users); $i++){
            for($j=0; $j<=count($houses); $j++){
                
                if(    ( $this->compare_min($houses[$j]['House']['area'], $users[$i]['Preference']['area_min']))
                    && ( $this->compare_max($houses[$j]['House']['area'], $users[$i]['Preference']['area_max']))
                    && ( $this->compare_min($houses[$j]['House']['bedroom_num'], $users[$i]['Preference']['bedroom_num_min']))
                    && ( $this->compare_max($houses[$j]['House']['price'], $users[$i]['Preference']['price_max']))
                    && ( $this->compare_min($houses[$j]['House']['floor_id'], $users[$i]['Preference']['floor_id_min']) )
            
                    && ( $this->compare_pref_min($houses[$j]['House']['bathroom_num'], $users[$i]['Preference']['bathroom_num_min'])) //not comp
                    && ( $this->compare_pref_min($houses[$j]['House']['construction_year'], $users[$i]['Preference']['construction_year_min']) ) //not comp
                    && ( $this->compare_pref_min($houses[$j]['House']['rent_period'], $users[$i]['Preference']['rent_period_min']) ) //not comp
                    && ( $this->compare_checkbox_null($houses[$j]['House']['solar_heater'], $users[$i]['Preference']['pref_solar_heater']) ) //not comp
                    && ( $this->compare_checkbox_null($houses[$j]['House']['aircondition'], $users[$i]['Preference']['pref_aircondition']) )
                    && ( $this->compare_checkbox_null($houses[$j]['House']['garden'], $users[$i]['Preference']['pref_garden']) )
                    && ( $this->compare_checkbox_null($houses[$j]['House']['parking'], $users[$i]['Preference']['pref_parking']) )
                    && ( $this->compare_checkbox_null($houses[$j]['House']['security_doors'], $users[$i]['Preference']['pref_security_doors']) )
                    && ( $this->compare_checkbox_null($houses[$j]['House']['storeroom'], $users[$i]['Preference']['pref_storeroom']) )
                    
                    && ( $this->compare_equal($houses[$j]['House']['house_type_id'], $users[$i]['Preference']['pref_house_type_id']))
                    && ( $this->compare_equal($houses[$j]['House']['heating_type_id'], $users[$i]['Preference']['pref_heating_type_id']))
                    
                    && ( $this->compare_equal_null($houses[$j]['House']['municipality_id'], $users[$i]['Preference']['pref_municipality'])) //not OK
                    && ( $this->shared_pay($houses[$j]['House']['shared_pay'], $users[$i]['Preference']['pref_shared_pay']) )
                    
                    && ( $this->compare_date_min($houses[$j]['House']['availability_date'], $users[$i]['Preference']['availability_date_min']) )
                    
                    && ( $this->compare_checkbox($houses[$j]['House']['furnitured'], $users[$i]['Preference']['pref_furnitured']) )
                    && ( $this->compare_checkbox($houses[$j]['House']['disability_facilities'], $users[$i]['Preference']['pref_disability_facilities']) )

                    && ( $this->has_photo($houses[$j]['House']['default_image_id'], $users[$i]['Preference']['pref_has_photo']))
                    
                )
                {
                    $email_users[$users['Profile']['email']] = $houses['House']['id'];
                }
            }
        }

        pr($email_users);die();
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
