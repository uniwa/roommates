<!DOCTYPE HTML>

<html>
<head>
    <title><?php echo $title_for_layout?></title>
    <meta charset="utf-8">
	<base href="<?php echo Router::url('/'); ?>" />

    <?php
    echo $this->Html->css('global');
    echo $html->css(array('fancybox/jquery.fancybox-1.3.4'), 'stylesheet', array('media' => 'screen'));
    echo $this->Html->script('jquery');
    echo $this->Html->script('jquery.fancybox-1.3.4.pack');
    echo $this->Html->script('jquery.easing-1.3.pack');
    echo $this->Html->script('jquery.mousewheel-3.0.4.pack');
    echo $this->Html->script('jQuery.fileinput');
    echo $this->Html->script('jquery.autogrowtextarea');
    echo $this->Html->script('main');
    echo $scripts_for_layout;
    ?>
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
