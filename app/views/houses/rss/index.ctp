<?php
$this->set('documentData', array(
                'xmlns:dc' => 'http://purl.org/dc/elements/1.1/'));
$this->set('channelData', array(
                'title' => __("Πρόσφατες καταχωρήσεις σπιτιών.", true),
                'link' => $this->Html->url('/', true),
                'description' => __("Πρόσφατες καταχωρήσεις σπιτιών.", true),
                'language' => 'el'));

foreach ($houses as $house) {
    $houseTime = strtotime($house['House']['modified']);

    $houseLink = array(
        'controller' => 'houses',
        'action' => 'view',
        $house['House']['id']
    );

    $houseTitle = "{$house['HouseType']['type']}-{$house['House']['area']}τμ";

    $bodyText = "Διεύθυνση : {$house['House']['address']}<br />
                 Τιμή : {$house['House']['price']}<br />
                 Διαθεσιμότητα : {$house['House']['availability_date']}";

    /* sanitize body */
    App::import('Sanitize');
    $bodyText = Sanitize::stripAll($bodyText);

    echo $this->Rss->item(array(), array(
        'title' => $houseTitle,
        'link' => $houseLink,
        'description' => $bodyText,
        'pubDate' => $house['House']['created']));
    }
?>
