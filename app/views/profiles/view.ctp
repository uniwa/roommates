<div class="profile">
    <p class="name"><?php echo $profile['Profile']['firstname'] . " " . $profile['Profile']['lastname'] ?><span
            class="age"> - Ηλικία: <?php echo $profile['Profile']['age'] ?></span></p>
    <img src="<?php echo $this->webroot; ?>img/profile_avatar.png" alt="Profile Picture" class="avatar"/>

    <p class="mail">email: <?php echo $profile['Profile']['email'] ?></p>

    <?php if ($profile['Profile']['sex'])
        echo '<p class="female">Γυναίκα.</p>';
    else
        echo '<p class="male">Άνδρας.</p>';
    ?>
    <p class="tel">Τηλέφωνο: <?php echo $profile['Profile']['phone'] ?></p>

    <p class="smoking"> <?php if (!$profile['Profile']['smoker']) echo "Δεν"; ?> Είμαι Καπνιστής.</p>

    <p class="pet"> <?php if (!$profile['Profile']['pet']) echo "Δεν" ?> 'Εχω Κατοικίδιο.</p>

    <p class="kid"> <?php if (!$profile['Profile']['child']) echo "Δεν" ?> 'Εχω παιδί.</p>

    <p class="couple"><?php if (!$profile['Profile']['couple']) echo "Δεν" ?>Είμαστε Ζευγάρι.</p>

    <p class="rmates">Επιθυμώ να συγκατοικήσω με το πολυ <?php echo $profile['Profile']['max_roommates'] ?> άτομα.</p>
</div>