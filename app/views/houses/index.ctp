<?php
    $html->script('sorting', false);
?>

<!--
<div id='top-frame' class='frame'>
	<div id='top-title' class='title'>
	</div>
	<div id='options' class='options'>
	</div>
</div>
-->

<div id='bottom-frame' class='frame'>
    <div class='frame-container'>
        <div id='bottom-title' class='title'>
            <h1>Kατάλογος Σπιτιών</h1>
        </div>
        <div id='bottom-subtitle' class='subtitle'>
            <?php
                $count = count($houses);
                if($count == 0){
                    $foundmessage = "Δεν βρέθηκαν σπίτια";
                }else{
                    if($count == 1){
                        $postfound = 'ε ';
                        $posthomes = '';
                    }else{
                        $postfound = 'αν ';
                        $posthomes = 'α';
                    }
                    $foundmessage = "Βρέθηκ".$postfound.$count." σπίτι".$posthomes."\n";
                }
                echo $foundmessage;
            ?>
        </div>
        <div class='order-by house-ord'>
            <?php
                echo $this->Form->input('orderΒy', array(
                    'label' => 'Ταξινόμηση με: ',
                    'options' => $order_options['options'],
					'selected' => $order_options['selected']));
             ?>
        </div>
        <div class="pagination">
            <ul>
            <?php
                /* show first page */
                //echo $paginator->first('⇤ Πρώτη ');
                /* show the previous link */
                echo $paginator->prev('« Προηγούμενη ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                /* show pages */
                echo $paginator->numbers(array('first' => 3, 'last' => 3, 'modulus' => 4, 'separator' => ' ', 'tag' => 'li'));
                /* Shows the next link */
                echo $paginator->next(' Επόμενη » ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                /* show last page */
                //echo $paginator->last('Τελευτευταία ⇥');
                /* prints X of Y, where X is current page and Y is number of pages */
                //echo " Σελίδα ".$paginator->counter(array('separator' => ' από '));
            ?>
            </ul>
        </div>
        <div id='results-houses' class='results'>
            <ul>
                <?php foreach ($houses as $house): ?>
                <li>
                    <div class='card'>
                        <div class='card-inner'>
                            <div class='house-pic'>
							<?php
								$house_id = $house['House']['id'];
								$house_image = 'house.gif';
								if(isset($images[$house_id])){
									$house_image = 'uploads/thumbnails/'.$images[$house_id];
								}
								echo $this->Html->image($house_image, array('alt' => 'house image', 'height' => 70));
							?>
                            </div>
                        <div class='house-info'>
                            <div class='house-name'>
                                <?php
                                    echo $this->Html->link($house['House']['address'],
                                        array('controller' => 'houses', 'action' => 'view', $house['House']['id']));
                                ?>
                            </div>
                            <div class='house-details'>
                                <?php
                                    echo "Δήμος " . $house['Municipality']['name'] . " <br />";
                                    echo $house['HouseType']['type'].", ".$house['House']['area']." τ.μ.<br />\n";
                                    echo $house['Floor']['type'].", ".$house['House']['price']." ευρώ<br />\n";
				    echo "Συνολικά φιλοξενεί " . $house['House']['total_places'] ." άτομα<br />\n";
                                ?>
                            </div>
                            <div class='house-house'>
                            </div>
                        </div>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>

            </ul>
        </div>	
        <div class="pagination">
            <ul>
            <?php
                /* show first page */
                //echo $paginator->first('⇤ Πρώτη ');
                /* show the previous link */
                echo $paginator->prev('« Προηγούμενη ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                /* show pages */
                echo $paginator->numbers(array('first' => 3, 'last' => 3, 'modulus' => 4, 'separator' => ' ', 'tag' => 'li'));
                /* Shows the next link */
                echo $paginator->next(' Επόμενη » ', array('tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
                /* show last page */
                //echo $paginator->last('Τελευτευταία ⇥');
                /* prints X of Y, where X is current page and Y is number of pages */
                //echo " Σελίδα ".$paginator->counter(array('separator' => ' από '));
            ?>
            </ul>
        </div>
    </div>
</div>

<?php //echo $this->Html->link("Προσθήκη Σπιτιού", array('action' => 'add')); ?>
