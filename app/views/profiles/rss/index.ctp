<?php
$this->set('documentData', array(
                'xmlns:dc' => 'http://purl.org/dc/elements/1.1/'));
$this->set('channelData', array(
                'title' => __("Πρόσφατες εγγραφές χρηστών.", true),
                'link' => $this->Html->url('/', true),
                'description' => __("Πρόσφατες εγγραφές χρηστών.", true),
                'language' => 'el'));

foreach ($profiles as $profile) {
    /* ignore modified profiles */
    $profileTime = strtotime($profile['Profile']['created']);

    $profileLink = array(
        'controller' => 'profiles',
        'action' => 'view',
        $profile['Profile']['id']
    );

    $profileTitle = "{$profile['Profile']['gender']}, {$profile['Profile']['age']}ετών";

    $bodyText = "<strong>Μέγιστος επιθυμητός αριθμός συγκατοίκων:</strong> {$profile['Profile']['max_roommates']}";

    echo $this->Rss->item(array(), array(
        'title' => $profileTitle,
        'link' => $profileLink,
        'description' => $bodyText,
        'pubDate' => $profile['Profile']['created']));
    }
?>
