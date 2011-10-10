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
        <h1>Εθελοντικές Δράσεις</h1>

        <h2>Υπηρεσίες δικτύωσης με επίκεντρο εθελοντικές δράσεις</h2>

        <a href="#" title=""><img class="title" src="<?php echo $this->webroot; ?>img/logo.png" alt=""/></a>

    </div>
    <!-- /#logo -->

    <div id="navigation" class="column">

        <ul id="nav">
            <li class="page_item current_page_item home">
                <a href="#"><span>Αρχική</span></a>
            </li>
            <li>
                <?php echo $this->Html->link('Ολα τα σπίτια', array(
                                                                   'controller' => 'houses',
                                                                   'action' => 'index')); ?>

            </li>


            <li>


                <?php echo $this->Html->link(' Το προφίλ μου', array(
                                                                   'controller' => 'profiles',
                                                                   'action' => 'view',
                                                                   $userid,
                                                               )); ?>
            </li>

            <li>
                <?php echo $this->Html->link('Αναζήτηση Συγκατοίκου', array(
                                                                           'controller' => 'profiles',
                                                                           'action' => 'search')); ?>

            </li>

            <?php if ($this->Session->read('Auth.User')) {

            echo '<li>';
            echo $this->Html->link('Αποσύνδεση', array('controller' => 'users',
                                                      'action' => 'logout'));
            echo '</li>';
        }?>


            <li class="rss">
                <a href="#" title="Subscribe"><img src="<?php echo $this->webroot; ?>img/rss.png"
                                                   alt="RSS-feed"/></a>
            </li>



        </ul>
        <!-- /#nav -->


    </div>
    <!-- /#navigation -->

    <div id="content" class="column">


        <?php echo $content_for_layout ?>

    </div>
    <!-- /#content -->

</div>


</body>
</html>



