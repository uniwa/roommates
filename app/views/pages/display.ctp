<style>
    .sectionTitle{
        clear: both;
        padding: 24px 0px 0px 8px;
        font-size: 1.3em;
        font-weight: bold;
        text-align: center;
        color: #000;
    }

    .result-cont{
        margin: 2px auto;
        height: 100px;
    }
</style>

<div id='main-inner'>
    <?php
        if(isset($housesPreferred)){
            if(!empty($housesPreferred)){
    ?>
    <div class='sectionTitle'>
        <h2>Σπίτια που ταιριάζουν στις προτιμήσεις μου</h2>
    </div>
    <ul id='lastPreferred'>
        <?php
                foreach($housesPreferred as $house){
        ?>
                <li class='result-cont'>
                    <div class='result'>
                        <div class='result-photo'>
                        <div class='result-photo-wrap'>
                        <div class='result-photo-cont'>
                        <div class='result-photo-inner'>
                            <?php
							    // thumbnail icon if found
							    $house_id = $house['House']['id'];
							    $house_image = 'house.gif';
                                if(!empty($house['Image'][0]['location'])) {
                                    $house_image = 'uploads/houses/'.$house_id.'/thumb_'.$house['Image'][0]['location'];
                                }
                                $altText = 'εικόνα '.$house['House']['address'];
							    $houseImage = $this->Html->image($house_image,
							        array('alt' => $altText));
							    echo $this->Html->link($houseImage, array(
							        'controller' => 'houses',
							        'action' => 'view', $house['House']['id']),
							        array('title' => $altText, 'escape' => false));
						    ?>
                        </div>
                        </div>
                        </div>
                        </div>
                        <div class='result-desc'>
                            <?php
                                $furnished = $house['House']['furnitured'] ? 'Επιπλωμένο' : 'Μη επιπλωμένο';
                                $houseid = $house['House']['id'];
                                $housePrice = $house['House']['price'];
                                $houseMunicipality = $municipality_options[$house['House']['municipality_id']];
                                $houseType = $house_types[$house['House']['house_type_id']];
                                $houseArea = $house['House']['area'];
                                $houseTypeArea = $houseType.', '.$houseArea.' τ.μ.';
                            ?>
                            <div class='desc-title houseClear'>
                                <?php
                                    echo $this->Html->link($houseTypeArea,
                                        array('controller' => 'houses','action' => 'view',$houseid));
                                ?>
                            </div>
                            <div class='desc-info'>
                                <?php
                                    echo 'Ενοίκιο '.$housePrice.'€, ';
                                    echo $furnished;
                                    echo '<br />Δήμος '.$houseMunicipality.'<br />';
                                    //echo 'Διεύθυνση '.$house['House']['address'].'<br />';
                                    if($house['House']['disability_facilities']) echo 'Προσβάσιμο από ΑΜΕΑ<br />';
                                    if ($house['User']['role'] != 'realestate') {
                                        echo 'Διαθέσιμες θέσεις '.
                                            $house['House']['free_places'].'<br />';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </li>
        <?php
                } // foreach $housesPrefs
        ?>
    </ul>
    <?php
            } // !empty $housesPrefs
        } // isset($housesPrefs)
        if(isset($housesModified)){
    ?>
    <div class='sectionTitle'>
        <h2>Πρόσφατες καταχωρήσεις σπιτιών</h2>
    </div>
    <ul id='lastModified'>
        <?php
            foreach($housesModified as $house){
        ?>
                <li class='result-cont result-dense'>
                    <div class='result'>
                        <div class='result-photo'>
                        <div class='result-photo-wrap'>
                        <div class='result-photo-cont'>
                        <div class='result-photo-inner'>
                            <?php
							    // thumbnail icon if found
							    $house_id = $house['House']['id'];
							    $house_image = 'house.gif';
                                if(!empty($house['Image'][0]['location'])){
                                    $house_image = 'uploads/houses/'.$house_id.'/thumb_'.$house['Image'][0]['location'];
                                }
                                $altText = 'εικόνα '.$house['House']['address'];
						        $houseImage = $this->Html->image($house_image,
						            array('alt' => $altText));
						        echo $this->Html->link($houseImage, array(
						            'controller' => 'houses',
						            'action' => 'view', $house['House']['id']),
						            array('title' => $altText, 'escape' => false));
						    ?>
                        </div>
                        </div>
                        </div>
                        </div>
                        <div class='result-desc'>
                            <?php
                                $furnished = $house['House']['furnitured'] ? 'Επιπλωμένο' : 'Μη επιπλωμένο';
                                $houseid = $house['House']['id'];
                                $housePrice = $house['House']['price'];
                                $houseMunicipality = $house['Municipality']['name'];
                                $houseType = $house['HouseType']['type'];
                                $houseArea = $house['House']['area'];
                                $houseTypeArea = $houseType.', '.$houseArea.' τ.μ.';
                            ?>
                            <div class='desc-title houseClear'>
                                <?php
                                    echo $this->Html->link($houseTypeArea,
                                        array('controller' => 'houses','action' => 'view',$houseid));
                                ?>
                            </div>
                            <div class='desc-info'>
                                <?php
                                    echo 'Ενοίκιο '.$housePrice.'€, ';
                                    echo $furnished;
                                    echo '<br />Δήμος '.$houseMunicipality.'<br />';
                                    //echo 'Διεύθυνση '.$house['House']['address'].'<br />';
                                    if($house['House']['disability_facilities']) echo 'Προσβάσιμο από ΑΜΕΑ<br />';
                                    if ($house['User']['role'] != 'realestate') {
                                        echo 'Διαθέσιμες θέσεις '.
                                            $house['House']['free_places'].'<br />';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </li>
        <?php
            } // foreach $housesModified
        ?>
    </ul>
    <?php
        } // isset($housesModified)
    ?>
</div>

