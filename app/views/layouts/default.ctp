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
//        echo $html->css(array('fancybox/jquery.fancybox-1.3.4'), 'stylesheet', array('media' => 'screen'));
//        echo $this->Html->script('jquery');
//        echo $this->Html->script('jquery.fancybox-1.3.4.pack');
//        echo $this->Html->script('jquery.easing-1.3.pack');
//        echo $this->Html->script('jquery.mousewheel-3.0.4.pack');
//        echo $this->Html->script('jQuery.fileinput');
//        echo $this->Html->script('jquery.autogrowtextarea');
//        echo $this->Html->script('main');
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
        <div id='footer'>
            <div id='footer-menu'>
                <ul>
                    <li class='menu-item menu-footer'>
                        όροι χρήσης
                    </li>
                    <li class='menu-item menu-footer'>
                        οδηγίες
                    </li>
                </ul>
            </div>
            <div id='footer-main'>
            </div>
        </div>
    </div>
</body>
</html>

