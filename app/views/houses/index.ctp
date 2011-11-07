<style>
    .search-title{
/*        margin: 12px 0px 8px 48px;*/
        margin: 12px auto 0px auto;
        text-align: center;
        font-size: 1.2em;
        font-weight: bold;
    }

    .search-subtitle{
/*        margin: 0px 0px 12px 64px;*/
        margin: 8px auto 24px auto;
        text-align: center;
        font-size: 1.2em;
        font-style: italic;
    }
    
    .pagination{
        margin: 0px auto 12px auto;
        text-align: center;
    }
    
    .pagination ul li{
        display: inline;
    }

    .pagination ul li.current{
        border: 1px solid #59A4D8;
        padding: 0px 2px 0px 2px;
    }
    
    .pagination ul li.disabled{
        color: #aaa;
    }
</style>

<?php
    $html->script('sorting', false);
?>

<div id='main-inner'>
    <div class='results'>
        <div class="pagination">
        <?php if(isset($houses)){ ?>
        <div class='search-title'>
            <h2>Όλα τα σπίτια</h2>
        </div>
        <?php
            $count = $this->Paginator->counter(array('format' => '%count%'));
            $foundmessage = 'Δεν βρέθηκαν σπίτια';
            if($count == 1) {
                $foundmessage = 'Βρέθηκε '.$count.' σπίτι';
            }else{
                $foundmessage = 'Βρέθηκαν '.$count.' σπίτια';
            }
        ?>
        <div class='search-subtitle'>
            <?php echo $foundmessage; ?>
        </div>
            <ul>
                <?php
                    /* show first page */
                    //echo $paginator->first('⇤ Πρώτη ');
                    /* show the previous link */
                    echo $paginator->prev('« Προηγούμενη ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                    /* show pages */
                    echo $paginator->numbers(array('first' => 3, 'last' => 3, 'modulus' => '4', 'separator' => ' ', 'tag' => 'li'));
                    /* Shows the next link */
                    echo $paginator->next(' Επόμενη » ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                    /* show last page */
                    //echo $paginator->last('Τελευτευταία ⇥');
                    /* prints X of Y, where X is current page and Y is number of pages */
                    //echo " Σελίδα ".$paginator->counter(array('separator' => ' από '));
                    ?>
            </ul>
        </div>
        <ul>
            <?php foreach($houses as $house){ ?>
            <li class='result-cont'>
                <div class='result'>
                    <div class='result-photo'>
                        <?php
							// thumbnail icon if found
							$house_id = $house['House']['id'];
							$house_image = 'house.gif';
                            if(!empty($house['Image']['location'])) {
                                $house_image = 'uploads/houses/'.$house_id.'/thumb_'.$house['Image']['location'];
                            }
							echo $this->Html->image($house_image, array('alt' => 'εικόνα '.$house['House']['address']));
						?>
                    </div>
                    <div class='result-desc'>
                        <div class='desc-title'>
                            <?php
                                echo $this->Html->link($house['House']['address'],
                                    array('controller' => 'houses','action' => 'view',$house['House']['id']));
                            ?>
                        </div>
                        <div class='desc-info'>
                            <?php
                                echo 'Ενοίκιο '.$house['House']['price'].'€, Εμβαδόν '.$house['House']['area'].' τ.μ. ';
                                echo $house['House']['furnitured'] ? 'Επιπλωμένο' : 'Μη επιπλωμένο';
                                echo '<br />Δήμος '.$house['Municipality']['name'].'<br />';
                                if($house['House']['disability_facilities']) echo 'Προσβάσιμο από ΑΜΕΑ<br />';
                                echo 'Διαθέσιμες θέσεις '.$house['House']['free_places'].'<br />';
                            ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php } // foreach $results ?>
        </ul>
        <div class="pagination">
            <ul>
                <?php
                    /* show first page */
                    //echo $paginator->first('⇤ Πρώτη ');
                    /* show the previous link */
                    echo $paginator->prev('« Προηγούμενη ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                    /* show pages */
                    echo $paginator->numbers(array('first' => 3, 'last' => 3, 'modulus' => '4', 'separator' => ' ', 'tag' => 'li'));
                    /* Shows the next link */
                    echo $paginator->next(' Επόμενη » ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                    /* show last page */
                    //echo $paginator->last('Τελευτευταία ⇥');
                    /* prints X of Y, where X is current page and Y is number of pages */
                    //echo " Σελίδα ".$paginator->counter(array('separator' => ' από '));
                ?>
            </ul>
        </div>
        <?php
            } // isset($houses)
        ?>
    </div>
</div>

