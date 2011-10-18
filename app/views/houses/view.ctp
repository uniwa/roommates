<div class="profile houseProfile">

<div class="leftCol">

        <div class="defaultimage">
            <?php
                       if (isset($images[0]))
            echo $this->Html->image('uploads/houses/' . $house['House']['id'] . '/thumb_' . $images[0]['Image']['location'], array('alt' => 'house image'));
        else
            echo $this->Html->image('homedefault.png', array('alt' => 'house image')); ?>

    </div>

    <table class="info-block">
        <tr>
            <th>Διεύθυνση:</th>
            <td><?php echo $house['House']['address']?></td>
        </tr>
        <tr>
            <th>Δήμος:</th>
            <td> <?php echo $house['Municipality']['name']?></td>
        </tr>
        <tr>
            <th>Τ.Κ.:</th>
            <td> <?php echo $house['House']['postal_code']?></td>
        </tr>
        <tr>
            <th>Τύπος:</th>
            <td> <?php echo $house['HouseType']['type']?></td>
        </tr>
        <tr>
            <th>Εμβαδόν:</th>
            <td> <?php echo $house['House']['area']?> τ.μ.</td>
        </tr>
        <tr>
            <th>Υπνοδωμάτια:</th>
            <td> <?php echo $house['House']['bedroom_num']?></td>
        </tr>
        <tr>
            <th>Μπάνια:</th>
            <td> <?php echo $house['House']['bathroom_num']?></td>
        </tr>
        <tr>
            <th>Όροφος:</th>
            <td> <?php echo $house['Floor']['type']?></td>
        </tr>
        <tr>
            <th>Έτος κατασκευής:</th>
            <td> <?php echo $house['House']['construction_year']?></td>
        </tr>
        <tr>
            <th>Θέρμανση:</th>
            <td> <?php echo $house['HeatingType']['type']?></td>
        </tr>
        <tr>
            <th>Ενοίκιο:</th>
            <td> <?php echo $house['House']['price']?>€</td>
        </tr>
        <tr>
            <th>Διαθέσιμο από:</th>
            <td> <?php echo $time->format($format = 'd / m / Y', $house['House']['availability_date'])?></td>
        </tr>
        <tr>
            <th>Περίοδος ενοικίασης:</th>
            <td> <?php echo ($house['House']['rent_period']) ? $house['House']['rent_period'] . " μήνες"
                    : '-' ?></td>
        </tr>


        <!--   </table> <table class="info-block">     this line makes 2 tables into 1-->
        <!-- boolean fields -->

        <tr>
            <th>Ηλιακός:</th>
            <td>
                <span class="checkbox cb<?php echo $house['House']['solar_heater']?>">&nbsp;</td>
        </tr>

        <tr>
            <th>Επιπλωμένο:</th>
            <td>
                <span class="checkbox cb<?php echo $house['House']['furnitured']?>">&nbsp;</td>
        </tr>

        <tr>
            <th>Κλιματισμός:</th>
            <td>
                <span class="checkbox cb<?php echo $house['House']['aircondition']?>">&nbsp;</td>
        </tr>

        <tr>
            <th>Κήπος:</th>
            <td>
                <span class="checkbox cb<?php echo $house['House']['garden']?>">&nbsp;</td>
        </tr>

        <tr>
            <th>Parking:</th>
            <td>
                <span class="checkbox cb<?php echo $house['House']['parking']?>">&nbsp;</td>
        </tr>

        <tr>
            <th>Κοινόχρηστα:</th>
            <td>
                <span class="checkbox cb<?php echo $house['House']['shared_pay']?>">&nbsp;</td>
        </tr>

        <tr>
            <th>Πόρτα ασφαλείας:</th>
            <td>
                <span class="checkbox cb<?php echo $house['House']['security_doors']?>">&nbsp;</td>
        </tr>

        <tr>
            <th>Προσβάσιμο από ΑΜΕΑ:</th>
            <td>
                <span class="checkbox cb<?php echo $house['House']['disability_facilities']?>">&nbsp;</td>
        </tr>

        <tr>
            <th>Αποθήκη:</th>
            <td>
                <span class="checkbox cb<?php echo $house['House']['storeroom']?>">&nbsp;</td>
        </tr>


        <!-- availability -->
        <tr>
            <th>Διαμένουν:</th>
            <td>
                <?php echo Sanitize::html($house['House']['currently_hosting'])?>
                <?php echo $house['House']['currently_hosting'] == 1 ? 'άτομο' : 'άτομα'?>
            </td>
        </tr>

        <tr>
            <th>Διαθέσιμες θέσεις</th>
            <td> <?php echo Sanitize::html($house['House']['free_places'])?>
                (από <?php echo Sanitize::html($house['House']['total_places'])?> συνολικά θέσεις)
            </td>
        </tr>

        <tr>
            <th>Περιγραφή:</th>
            <td> <?php echo Sanitize::html($house['House']['description'])?></td>
        </tr>

    </table>


</div>
<!--left collumn-->


<div class="rightCol">
    <div class="actions">
        <?php
                             //if( ($this->Session->read( 'Auth.User.id') == $house['Profile']['user_id']) || ($this->Session->read('Auth.User.role') == 'admin') ){
            if (($this->Session->read('Auth.User.id') == $house['User']['id']) || ($this->Session->read('Auth.User.role') == 'admin')) {
                echo $html->link('Επεξεργασία', array('action' => 'edit', $house['House']['id']));
                echo $html->link('Διαγραφή', array('action' => 'delete', $house['House']['id']), null, 'Είστε σίγουρος/η;');
            }


            if (($this->Session->read('Auth.User.id') != $house['User']['id']) || ($this->Session->read('Auth.User.role') == 'admin')) {

                echo $this->Html->link('Προφίλ ιδιοκτήτη Αγγελίας', "/profiles/view/{$house['User']['Profile']['id']}");
            }
            ?>


    </div>

    <div class="gallery">

        <?php $i = 0; foreach ($images as $image): ?>
<ul class=ulimage>
        <li class="liimage">
            <?php echo $this->Html->link(
            $this->Html->image('uploads/houses/' . $house["House"]["id"] . "/thumb_" . $image['Image']['location'], array('alt' => 'house image')),
            '/img/uploads/houses/' . $house['House']['id'] . '/medium_'. $image['Image']['location'],
            array('class' => 'fancyImage', 'rel' => 'group', 'title' => 'description title', 'escape' => false)
        ); ?>
            <div class="imageactions">
                <?php 
                    if (($this->Session->read('Auth.User.id') == $house['User']['id']) || ($this->Session->read('Auth.User.role') == 'admin')) {
                        echo $this->Html->link(__('Διαγραφή', true), array('controller' => 'images', 'action' => 'delete', $image['Image']['id']), array('class' => 'thumb_img_action'), sprintf(__('Είστε σίγουρος;', true)));
                    }
                ?>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>

        <div class="actions">


        <?php

            if (($this->Session->read('Auth.User.id') == $house['User']['id']) || ($this->Session->read('Auth.User.role') == 'admin')) {
                echo $this->Html->link(__('Προσθήκη νέας εικόνας', true), array('controller' => 'images', 'action' => 'add', $house['House']['id']));
            }
            ?>
        </div>


    </div>


</div>
<!--right column-->

</div>

<div class="clear-both"></div>
<!-- <div class="houseProfile">
    <h2></h2> -->
        <?php
/*
    $i = 0;
    foreach ($images as $image):

        ?>

        <div class="galleryimage">
            <?php 
                echo $this->Html->link(
                    $this->Html->image("uploads/houses/" . $house['House']['id'] . "/thumb_"  . $image['Image']['location'], array('alt' => 'house image')),
                    '/img/uploads/houses/' . $house['House']['id'] . "/medium_" . $image['Image']['location'],
                    array('class' => 'fancyImage', 'rel' => 'group', 'title' => 'description title', 'escape' => false));
             ?>


            <div class="imageactions">
                <?php
                    if (($this->Session->read('Auth.User.id') == $house['User']['id']) || ($this->Session->read('Auth.User.role') == 'admin')) {
                        echo $this->Html->link(__('Διαγραφή', true),
                                array('controller' => 'images', 'action' => 'delete', 
                                      $image['Image']['id']),
                                      array('class' => 'thumb_img_action'), sprintf(__('Είστε σίγουρος;', true)));
                    }
                ?>
            </div>

        </div>
        <?php endforeach; ?>

    <div class="actions">


        <?php

            if (($this->Session->read('Auth.User.id') == $house['User']['id']) || ($this->Session->read('Auth.User.role') == 'admin')) {
                echo $this->Html->link(__('Προσθήκη νέας εικόνας', true), array('controller' => 'images', 'action' => 'add', $house['House']['id']));
            }*/
            ?>
<!--    </div> -->
</div>
