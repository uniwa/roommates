<h2>Λίστα Σπιτιών</h2>

<?php echo $this->Html->link("Προσθήκη Νέου Σπιτιού", array('action' => 'add'),array('class' => 'addButton')); ?>


<ul class="thelist">
    <?php foreach ($houses as $house): ?>

    <li>
        <div class="photo">
            <img src="<?php echo $this->webroot; ?>img/homedefault.png" alt="Home Picture" class="avatar"/>

        </div>
        <div class="info">
            <?php echo $this->Html->link($house['House']['address'],
                                         array('controller' => 'houses', 'action' => 'view',
                                              $house['House']['id'])); ?>

          <p> <?php echo $house['HouseType']['type']; ?></p>
          <p> <?php echo $house['Floor']['type']; ?></p>
        <p><?php echo $house['House']['area']; ?> τ.μ.</p>

        </div>

        <div class="aboutme">

        </div>

    </li>



    <?php
        endforeach;
    ?>

</ul>
