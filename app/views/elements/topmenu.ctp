<div id='top-menu'>
    <ul>

        <?php if (isset($selected_action) && $selected_action == 'profiles_search' ) { ?>
            <li class='menu-item menu-main menu-selected'>
        <?php } else { ?>
            <li class='menu-item menu-main'>
        <?php } ?>
                <?php
                    echo $this->Html->link( 'Αναζήτηση συγκατοίκων',
                                            array(  'controller' => 'profiles',
                                                    'action' => 'search'));
                ?>
            </li>

        <?php if (isset($selected_action) && $selected_action == 'houses_search') { ?>
            <li class='menu-item menu-main menu-selected'>
        <?php } else { ?>
            <li class='menu-item menu-main'>
        <?php } ?>
                <?php
                    echo $this->Html->link( 'Αναζήτηση σπιτιών',
                                            array(  'controller' => 'houses',
                                                    'action' => 'search'));
                ?>
            </li>
    </ul>
</div>

