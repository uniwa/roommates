<?php

$this->set('documentData', array(
                'xmlns:atom' => 'http://www.w3.org/2005/Atom'));

$channel = array(
                'title' => __("Πρόσφατες εγγραφές χρηστών.", true),
                'link' => $this->Html->url('/', true), /* home URL */
                'description' => __("Πρόσφατες εγγραφές χρηστών.", true),
                'language' => 'el',
                'atom:link' => array(
                   'attrib' => array(
                      'href' =>  $this->Html->url('/profiles/index.rss', true),
                      'rel' => 'self',
                      'type' => 'application/rss+xml'))
                );

$this->set('channelData', $channel);

$genderList = array('άνδρας', 'γυναίκα');

foreach ($profiles as $profile) {
    /* ignore modified profiles */
    $profileTime = strtotime($profile['Profile']['created']);

    $profileLink = array(
        'controller' => 'profiles',
        'action' => 'view',
        $profile['Profile']['id']
    );

    $profileTitle = "{$genderList[$profile['Profile']['gender']]}, {$profile['Profile']['age']}ετών";

    $bodyText = "<strong>Μέγιστος επιθυμητός αριθμός συγκατοίκων:</strong> {$profile['Profile']['max_roommates']}";

    echo $this->Rss->item(array(), array(
        'title' => $profileTitle,
        'link' => $profileLink,
        'description' => $bodyText,
        'pubDate' => $profile['Profile']['created']));
    }
?>
