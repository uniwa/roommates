<?php
    $html->script('sorting', false);
?>

<div id='bottom-frame' class='frame'>
    <div class='frame-container'>
        <div id='bottom-title' class='title'>
            <h1>Kατάλογος Δημόσιων Προφίλ</h1>
        </div>
        <div id='bottom-subtitle' class='subtitle'>
            <?php
				$count = $this->Paginator->counter(array('format' => '%count%'));//count($profiles);
                if($count == 0){
                    $foundmessage = "Δεν βρέθηκαν προφίλ";
                }else{
                    $postfound = ($count == 1)?'ε ':'αν ';
                    $foundmessage = "Βρέθηκ".$postfound.$count." προφίλ\n";
                }
                echo $foundmessage;
            ?>
        </div>
        <div class='order-by profile-ord'>
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
        <div id='results-profiles' class='results'>
            <ul>
                <?php foreach ($profiles as $profile): ?>
                <li>
                    <div class='card'>
                        <div class='card-inner'>
                        <?php
                            $gender = ($profile['Profile']['gender'])?'fe':'';
                            echo "<div class='profile-pic ".$gender."male'>\n";
                        ?>
                        </div>
                        <div class='profile-info'>
                            <div class='profile-name'>
                                <?php
                                    echo $this->Html->link($profile['Profile']['firstname'].' '.$profile['Profile']['lastname'],
                                        array('controller' => 'profiles', 'action' => 'view', $profile['Profile']['id']));
                                ?>
                            </div>
                            <div class='profile-details'>
                                <?php
                                    echo $profile['Profile']['age'].", ";
                                    $gender = ($profile['Profile']['gender'])?'γυναίκα':'άνδρας';
                                    echo $gender."<br />\n";
									$email = $profile['Profile']['email'];
									$emailUrl = $this->Html->link($email, 'mailto:'.$email);
                                    echo "email: ".$emailUrl."<br />\n";
									echo "επιθυμητοί συγκάτοικοι: ".$profile['Profile']['max_roommates']."<br />\n";
                                ?>
                            </div>
                            <div class='profile-house'>
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
    </div>
</div>
