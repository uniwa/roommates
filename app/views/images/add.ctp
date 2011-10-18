
<?php echo $html->css(array('fileUploadPlugin'), 'stylesheet', array('media' => 'screen'));
?>

<h2>Προσθήκη νέας εικόνας</h2>

<div>
<?php // echo $this->Form->create('Image');?>
<?php echo $this->Form->create('Image',array('type' => 'file', 'action' => 'add/'.$house_id)); ?>
	<fieldset class="file-upload ">
	<?php
		echo $this->Form->input('location', array('type' => 'file' , 'label' => 'παρακαλώ επιλέξτε...', "id" => "fileupload"));
		//echo $form->input('location', array('type' => 'file'));
		echo $this->Form->input('house_id', array('type' => 'hidden' , "value" => $house_id));
	?>
<?php echo $this->Form->end(__('Ανέβασμα εικόνας', true));?>
</fieldset>
