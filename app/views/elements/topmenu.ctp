<div id='top-menu'>
    <ul>
        <li class='menu-item menu-main menu-selected'>
            <?php echo $this->Html->link('Αναζήτηση συγκατοίκων', array(
                                                                       'controller' => 'profiles',
                                                                       'action' => 'search')); ?>
        </li>
        <li class='menu-item menu-main'>
            <?php echo $this->Html->link('Αναζήτηση σπιτιών', array(
                                                                       'controller' => 'houses',
                                                                       'action' => 'search')); ?>
        </li>
    </ul>
</div>

