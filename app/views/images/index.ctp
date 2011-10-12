<?php

 echo $this->Html->script('jquery');
        echo $this->Html->script('jquery.fancybox-1.3.4.pack');
        echo $this->Html->script('jquery.easing-1.3.pack');
        echo $this->Html->script('jquery.mousewheel-3.0.4.pack');
        echo $this->Html->script('main');
echo $html->css(array('fancybox/jquery.fancybox-1.3.4'), 'stylesheet', array('media' => 'screen'));
?>


<div>
	<h2></h2>
	<?php
	$i = 0;
	foreach ($images as $image):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}


	?>

    <div class="galleryimage">
        <?php echo $this->Html->link(
        $this->Html->image('uploads/thumbnails/' . $image['Image']['location'], array('alt' => 'house image')),
        '/img/uploads/medium/' . $image['Image']['location'],
        array('class' => 'fancyImage', 'rel' => 'group', 'title' => 'description title','escape' => false)
        ); ?>


        <div class="imageactions">
        <?php echo $this->Html->link(__('Διαγραφή', true), array('action' => 'delete', $image['Image']['id']), array('class' => 'thumb_img_action'), sprintf(__('Είστε σίγουρος;', true))); ?>
	</div>

    </div>
	<?php endforeach; ?>

<div id="actions">
<?php echo $this->Html->link(__('Προσθήκη νέας εικόνας', true), array('action' => 'add')); ?>
</div>