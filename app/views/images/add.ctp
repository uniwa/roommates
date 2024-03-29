<?php
    echo $html->css(array('fileUploadPlugin'), 'stylesheet',
        array('media' => 'screen'));
?>

<h2>Προσθήκη νέας εικόνας</h2>
<div>
    <?php
        // echo $this->Form->create('Image');
        echo $this->Form->create('Image',array('type' => 'file', 'action' => 'add/'.$house_id));
    ?>
    <fieldset class="file-upload ">
	    <?php
		    echo $this->Form->input('location', array('type' => 'file' , 'label' => 'παρακαλώ επιλέξτε...', "id" => "fileupload"));
		    //echo $form->input('location', array('type' => 'file'));
		    echo $this->Form->input('house_id', array('type' => 'hidden' , "value" => $house_id));
            echo $this->Form->end(__('Ανέβασμα εικόνας', true));
        ?>
        <div class="img-info">
            <span class="bold">Σημείωση:</span> Επιτρέπονται μόνο εικόνες τύπου 
            <span class="bold">jpeg</span> και <span class="bold">png</span>
            με μέγιστο μέγεθος <span class="bold"><?php echo $max_size; ?>MB</span>.
        </div>
    </fieldset>
</div>
