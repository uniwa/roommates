<html>
<head>
    <title><?php echo $title_for_layout?></title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>

    <?php echo $scripts_for_layout ?>
    <?php echo $this->Html->css('global'); ?>
</head>
<body>
<div id="container">

    <div id="logo" class="column">
        <div id="vcard">
            <?php
            $userid = $this->Session->read('Auth.User.id');
            echo $this->Html->link(
                $this->Html->image("vcard.png", array("alt" => "Το προφίλ μου")),
                "/profiles/view/$userid",
                array('escape' => false)
            );
            ?>
        </div>
        <a href="#" title=""><img class="title" src="<?php echo $this->webroot; ?>img/logo.png" alt=""/></a>
    </div>
    <!-- /#logo -->
    <?php
        /* include navigation element */
        if ($this->Session->read("Auth.User") != NULL) { 
            echo $this->element('sidebar', array("userid" => $userid));
        }
    ?>
    <div id="content" class="column">
        <?php
            echo $this->Session->flash();
            echo $this->Session->flash('auth');
        ?>
        <?php echo $content_for_layout ?>
    </div>
    <!-- /#content -->
</div>
</body>
</html>
