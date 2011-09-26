<?php
$this->set('documentData', array(
                'xmlns:dc' => 'http://purl.org/dc/elements/1.1/'));
$this->set('channelData', array(
                'title' => __("Most Recent Houses", true),
                'link' => $this->Html->url('/', true),
                'description' => __("Most recent houses.", true),
                'language' => 'en-us'));

foreach ($houses as $house) {
    $houseTime = strtotime($house['House']['modified']);

    $houseLink = array(
        'controller' => 'houses',
        'action' => 'view',
        $house['House']['id']
    );

    echo $this->Rss->item(array(), array(
        'title' => $house['House']['address'],
        'link' => $houseLink,
        'pubDate' => $house['House']['created']));
    }
?>
