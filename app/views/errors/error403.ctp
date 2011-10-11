  <h2><?php echo $name; ?></h2>
  <p class="error">
    <strong>Error: </strong>
    <?php 
	if( isset( $message ) ){
		
		echo sprintf(__("%s", true) ,"<strong>'{$message}'</strong>");
	} else {

		echo sprintf(__("Access was forbidden to the requested address %s on this server.", true), "<strong>'{$url}'</strong>");
	}
    ?>
  </p>

