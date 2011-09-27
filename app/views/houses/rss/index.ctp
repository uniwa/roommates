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

    $houseTitle = "{$house['House']['address']}-{$house['HouseType']['type']}-{$house['House']['area']}τμ";
    echo $this->Rss->item(array(), array(
        'title' => $houseTitle,
        'link' => $houseLink,
        'pubDate' => $house['House']['created']));
    }
?>
