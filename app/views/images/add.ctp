<div>
<?php // echo $this->Form->create('Image');?>
<?php echo $this->Form->create('Image',array('type' => 'file')); ?>
	<fieldset>
 		<legend><?php __('Add Image'); ?></legend>
	<?php
		echo $this->Form->input('location', array('type' => 'file'));
		//echo $form->input('location', array('type' => 'file'));
		echo $this->Form->input('house_id', array('type' => 'text'));
	?>
	</fieldset>
    
    
<?php echo $this->Form->end(__('Submit', true));?>
