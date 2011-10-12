<div>
<?php // echo $this->Form->create('Image');?>
<?php echo $this->Form->create('Image',array('type' => 'file')); ?>
	<fieldset>
 		<legend><?php __('Προσθήκη νέας εικόνας'); ?></legend>
	<?php
		echo $this->Form->input('location', array('type' => 'file' , 'label' => 'παρακαλώ επιλέξτε...'));
		//echo $form->input('location', array('type' => 'file'));
		echo $this->Form->input('house_id', array('type' => 'hidden' , "value" => $house_id));
	?>
	</fieldset>
    
    
<?php echo $this->Form->end(__('Submit', true));?>
