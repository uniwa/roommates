<!doctype html>
<html>
<head>
    <title><?php echo $title_for_layout; ?></title>
    <?php echo $this->Html->charset('utf-8'); ?>
    <link href='http://fonts.googleapis.com/css?family=Didact+Gothic&subset=latin,greek' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Ubuntu+Mono:700&subset=latin,greek' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans&subset=latin,greek' rel='stylesheet' type='text/css'>
    <base href="<?php echo Router::url('/'); ?>"/>
    <?php
        echo $this->Html->meta('Σπίτια', '/houses/index.rss', array('type' => 'rss'));
        echo $this->Html->meta('favicon.ico', 'img/favicon.ico', array('type' => 'icon'));
        echo $this->Html->css('global');
        echo $this->Html->script('jquery');
        if(Configure::read('debug') != 0){
            echo $this->Html->script('debug');
        }
        /* fancybox: js gallery moved to house view only */
        echo $scripts_for_layout;
    ?>
</head>
<body>
    <div id='debug'>
        <?php
            if(Configure::read('debug') != 0){
                echo Configure::read('debugging');
            }
        ?>
    </div>
    <div id='top-menu-cont'>
        <?php
            $userid = $this->Session->read('Auth.User.id');
            $userNull = $this->Session->read("Auth.User") == NULL;
            $userBanned = $this->Session->read('Auth.User.banned');
            echo $this->element('topmenu', array("userid" => $userid));
            echo $this->element('topuser', array("userid" => $userid));
        ?>
    </div>
    <div id='container'>
        <?php
            echo $this->Session->flash();
            echo $this->Session->flash('auth');
        ?>
        <div id='header'>
            <div id='header-main'>
                <div class='header-title'>
                    <h1><?php echo $title_for_layout; ?></h1>
                </div>
            </div>
        </div>

        <div id='main-cont'>
            <div id='main'>
                <?php
                    if($userBanned){
                        echo $this->element('banned');
                    }
                ?>
                <!-- /#content -->
                <?php echo $content_for_layout; ?>
            </div>
        </div>

     <?php echo $this->element('footer'); ?>

    </div>
    <!-- Inlude Piwik element -->
    <?php echo $this->element('piwiki'); ?>
</body>
</html>

