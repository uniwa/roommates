<!doctype html>
<html>
<head>
    <title><?php echo $title_for_layout?></title>
    <?php echo $this->Html->charset('utf-8'); ?>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,greek' rel='stylesheet' type='text/css'>
    <base href="<?php echo Router::url('/'); ?>"/>
    <?php
//        echo $this->Html->meta('Σπίτια', '/houses/index.rss', array('type' => 'rss'));
        echo $this->Html->meta('favicon.ico', 'img/favicon.ico', array('type' => 'icon'));
        echo $this->Html->css('global');
        echo $this->Html->script('jquery');
        /* fancybox: js gallery moved to house view only */
        echo $scripts_for_layout;
    ?>
</head>
<body>
    <div id='top-menu-cont'>
        <?php
            $userid = $this->Session->read('Auth.User.id');
            if ($this->Session->read("Auth.User") != NULL) {
                echo $this->element('topmenu', array("userid" => $userid));
            }
        ?>
        <?php
            $userid = $this->Session->read('Auth.User.id');
            if ($this->Session->read("Auth.User") != NULL) {
                echo $this->element('topuser', array("userid" => $userid));
            }
        ?>
    </div>
    <div id='container'>
        <div id='header'>
            <div id='header-main'>
                <div class='header-title'>
                    <h1><?php echo $title_for_layout?></h1>
                </div>
            </div>
        </div>
        
        <div id='main-cont'>
            <div id='main'>
                <!-- /#content -->
                <?php echo $content_for_layout; ?>
            </div>
        </div>

     <?php echo $this->element('footer'); ?>

    </div>
</body>
</html>

