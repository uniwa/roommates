<?php
$this->set('documentData', array(
                'xmlns:atom' => 'http://www.w3.org/2005/Atom'));
$channel = array(
            'title' => __("Πρόσφατες καταχωρήσεις σπιτιών.", true),
            'link' => $this->Html->url('/', true),
            'description' => __("Υπηρεσία αναζήτησης συγκατοίκων ΤΕΙ Αθήνας.", true),
            'language' => 'el',
            'atom:link' => array(
                'attrib' => array(
                    'href' =>  $this->Html->url('/houses/index.rss', true),
                    'rel' => 'self',
                    'type' => 'application/rss+xml'))
            );

$this->set('channelData', $channel);

foreach ($houses as $house) {
    $houseTime = strtotime($house['House']['modified']);

    $houseLink = array(
        'controller' => 'houses',
        'action' => 'view',
        $house['House']['id']
    );

    $houseTitle = "{$house['HouseType']['type']}, {$house['House']['area']}τμ";

    $bodyText = "<strong>Δήμος:</strong> {$house['Municipality']['name']}<br />
                 <strong>Διεύθυνση:</strong> {$house['House']['address']}<br />
                 <strong>Ενοίκιο:</strong> {$house['House']['price']}€<br />
                 <strong>Διαθεσιμότητα:</strong> {$time->format(
                                    $format = 'd-m-Y',
                                    $house['House']['availability_date'])
                                 }";
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
