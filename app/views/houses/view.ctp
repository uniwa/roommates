<?php
    /* fancybox: js image gallery  */
    echo $this->Html->script('jquery.fancybox-1.3.4.pack');
    echo $this->Html->script('jquery.easing-1.3.pack');
    echo $this->Html->script('jquery.mousewheel-3.0.4.pack');
    echo $this->Html->script('jQuery.fileinput');
    echo $this->Html->script('jquery.autogrowtextarea');
    echo $this->Html->script('main');
    echo $this->Html->css('fancybox/jquery.fancybox-1.3.4.css', 'stylesheet', array("media"=>"all" ), false);
?>
<div class="house-gallery">
    <div class="default-image">
        <?php
        $empty_slots = 4 - count($images);
        /* defaukt image */
        if (isset($images[0])) {
            echo $this->Html->link(
                    $this->Html->image('uploads/houses/' . $house["House"]["id"] . "/thumb_" . $default_image_location, array('alt' => 'house image')),
                    '/img/uploads/houses/' . $house['House']['id'] . '/medium_'. $default_image_location,
                    array('class' => 'fancyImage', 'rel' => 'group', 'title' => 'description title', 'escape' => false)
                    );

            if ($this->Session->read('Auth.User.id') == $house['User']['id']) {
                echo "<div class='imageactions'>";
                echo $this->Html->link(__('Διαγραφή', true),
                                    array('controller' => 'images', 'action' => 'delete', $default_image_id),
                                        array('class' => 'thumb_img_delete'), sprintf(__('Είστε σίγουρος;', true))
                                    );
                echo "</div>";
            }
        }
        /* if don't have an image put placeholder */
        else {
            if ($this->Session->read('Auth.User.id') == $house['User']['id']) {
                /* placeholder with link to add image */
                echo $this->Html->link(
                        $this->Html->image('addpic.png', array('alt' => 'add house image', 'class' => 'img-placeholder')),
                        array('controller' => 'images', 'action' =>'add', $house['House']['id']),
                        array('class' => '', 'rel' => 'group', 'title' => 'description title', 'escape' => false)
                        );
            } else {
                /* empty placeholder without link to add image */
                echo $this->Html->image('addpic.png', array('alt' => 'add house image', 'class' => 'img-placeholder'));
            }
            $empty_slots -= 1;
        }
        ?>
    </div>
    <div class="image-list">
        <ul>
            <?php
                /* image placeholder */
                for ($i = 1; $i <= $empty_slots; $i++) {
                    echo '<li class="liimage">';
                    /* if we have access placeholder is a link to 'add image' */
                    if ($this->Session->read('Auth.User.id') == $house['User']['id']) {
                        echo $this->Html->link(
                            $this->Html->image('addpic.png', array('alt' => 'add house image', 'class' => 'img-placeholder')),
                            array('controller' => 'images', 'action' =>'add', $house['House']['id']),
                            array('class' => '', 'rel' => 'group', 'title' => 'description title', 'escape' => false)
                        );
                    /* empty placeholder without link to add image */
                    } else {
                        echo $this->Html->image('addpic.png', array('alt' => 'add house image', 'class' => 'img-placeholder'));
                    }
                    echo '<div class="imageactions">&nbsp;</div>';
                    echo "</li>\n";
                }
                $i = 0;
                foreach ($images as $image):
                    /* skip image if is the default one
                       the default image is shown on the left side of the image bar
                    */
                    if ($image['Image']['location'] == $default_image_location) {
                        continue;
                    }
            ?>
            <li class="liimage">
                <?php echo $this->Html->link(
                    $this->Html->image('uploads/houses/' . $house["House"]["id"] . "/thumb_" . $image['Image']['location'], array('alt' => 'house image')),
                    '/img/uploads/houses/' . $house['House']['id'] . '/medium_'. $image['Image']['location'],
                    array('class' => 'fancyImage', 'rel' => 'group', 'title' => 'description title', 'escape' => false)
                    );
                ?>

                <div class="imageactions">
                    <?php
                        /* image actions: set as default and delete */
                        if ($this->Session->read('Auth.User.id') == $house['User']['id']) {
                            echo $this->Html->link(__('Διαγραφή', true),
                                array('controller' => 'images', 'action' => 'delete',
                                    $image['Image']['id']),
                                    array('class' => 'thumb_img_delete'), sprintf(__('Είστε σίγουρος;', true)));
                            echo $this->Html->link('Προεπιλεγμένη',
                                    array('controller' => 'images', 'action' => 'set_default', $image['Image']['id']),
                                    array('class' => 'thumb_img_thumb'), null);
                        } else {
                            /* dummy div to align image actions */
                            echo '<div class="imageactions">&nbsp;</div>';
                        }
                    ?>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>

        <div class="actions">
        <?php
        /*
            if ($this->Session->read('Auth.User.id') == $house['User']['id']) {
                echo $this->Html->link(__('Προσθήκη νέας εικόνας', true), array('controller' => 'images', 'action' => 'add', $house['House']['id']));
            }
        */
        ?>
        </div>
    </div> <!-- end image-list -->
</div> <!-- end house-gallery -->

<div class="profile houseProfile">

<div class="house-left">
    <table class="house-info">
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
            <th>Ορατότητα:</th>
            <td>
                <?php
                    if($this->Session->read('Auth.User.id') == $house['User']['id']) {
                        if($house['House']['visible']) {
                            echo 'Είναι ορατό σε άλλους χρήστες και στις αναζητήσεις.';
                        } else {
                            echo 'Δεν είναι ορατό σε άλλους χρήστες και στις αναζητήσεις.';
                        }
                    }
                ?>
            </td>
        </tr>

        <tr>
            <th>Περιγραφή:</th>
            <td> <?php echo Sanitize::html($house['House']['description'])?></td>
        </tr>

    </table>


</div>
<!--left collumn-->

<div class="house-right">
    <div class="actions">
        <?php
            if ($this->Session->read('Auth.User.id') == $house['User']['id']) {
                echo $html->link('Επεξεργασία', array('action' => 'edit', $house['House']['id']));
                echo $html->link('Διαγραφή', array('action' => 'delete', $house['House']['id']), null, 'Είστε σίγουρος/η;');
            }

            if ($this->Session->read('Auth.User.id') != $house['User']['id']) {
                echo $this->Html->link('Προφίλ ιδιοκτήτη Αγγελίας', "/profiles/view/{$house['User']['Profile']['id']}");
            }
            ?>


    </div>


</div>
<!--right column-->

</div>

