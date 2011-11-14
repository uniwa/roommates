<?php
$this->set('documentData', array(
                'xmlns:atom' => 'http://www.w3.org/2005/Atom'));
$channel = array(
            'title' => __("Προσωποποιημένο RSS.", true),
            'link' => $this->Html->url('/', true),
            'description' => __("Υπηρεσία αναζήτησης συγκατοίκων ΤΕΙ Αθήνας.", true),
            'language' => 'el',
            'atom:link' => array(
                'attrib' => array(
                    'href' =>  $this->Html->url('/houses/search.rss', true),
                    'rel' => 'self',
                    'type' => 'application/rss+xml'))
            );

$this->set('channelData', $channel);

foreach ($results as $house) {
    $houseTime = strtotime($house['House']['modified']);

    $houseLink = array(
        'controller' => 'houses',
        'action' => 'view',
        $house['House']['id']
    );
    /* set title */
    $houseTitle = "{$house_types[$house['House']['house_type_id']]}, {$house['House']['area']}τ.μ.";

    /* set image if exists */
    if (isset($house['Image']['location'])) {
        $_imgpath = 'uploads/houses/' . $house['House']['id'] . '/thumb_' . $house['Image']['location'];
        $thumb = $this->Html->image($_imgpath, array('alt' => 'εικόνα '.$house['House']['address'], 'height' => 70)) . '<br />';
    }
    else {
        $thumb ="";
    }

    /* set the rest rss body */
    $_furnitured = $house['House']['furnitured'] ? '<strong>Είναι</strong> επιπλωμένο.' : '<strong>Μη</strong> επιπλωμένο.';
    $bodyText = "<strong>Δήμος:</strong> {$municipalities[$house['House']['municipality_id']]}<br />
                 <strong>Διεύθυνση:</strong> {$house['House']['address']}<br />
                 <strong>Ενοίκιο:</strong> {$house['House']['price']}€<br />
                 {$_furnitured} <br />
                 <strong>Διαθέσιμες θέσεις:</strong> {$house['House']['free_places']}τ.μ. <br />
                 <strong>Διαθεσιμότητα:</strong> {$time->format(
                                    $format = 'd-m-Y',
                                    $house['House']['availability_date'])}";
    /* sanitize body */
    App::import('Sanitize');
    $bodyText = Sanitize::stripAll($bodyText);
    $body = $thumb . $bodyText;

    echo $this->Rss->item(array(), array(
        'title' => $houseTitle,
        'link' => $houseLink,
        'description' => $body,
        'pubDate' => $house['House']['created']));
    }
?>
