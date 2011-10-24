  <h2><?php echo $name; ?></h2>
  <p class="error">
    <strong>Σφάλμα: </strong>
    <?php 
	if( isset( $message ) ){
		
		echo sprintf(__("%s", true) ,"<strong>'{$message}'</strong>");
	} else {

		echo sprintf(__("Η σελίδα %s δεν βρέθηκε.", true), "<strong>'{$url}'</strong>");
	}
    ?>
  </p>

