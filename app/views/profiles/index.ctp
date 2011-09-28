<h1>Kατάλογος Δημόσιων Προφίλ</h1>

<p><?php echo $this->Html->link("Προσθήκη Προφίλ", array('action' => 'add')); ?></p>
<p><?php echo $this->Html->link("Αναζήτηση Συγκατοίκων", array('action' => 'search')); ?></p>

<p><?php $sexmale = 'Άνδρας'; ?></p>
<p><?php $sexfemale = 'Γυναίκα'; ?></p>

<p><?php $yes = 'Ναι'; ?></p>
<p><?php $no = 'Όχι'; ?></p>

<table>
	<tr>
        <td>Όνομα</td>
	<td>Επίθετο</td>
        <td>Email</td>
        <td>Ηλικία</td>
        <td>Φύλο</td>
        <td>Τηλέφωνο</td>
        <td>Καπνιστής</td>
        <td>Κατοικίδιο</td>
        <td>Παιδί</td>
        <td>Ζευγάρι</td>
        <td>Συγκάτοικοι</td>
	</tr>

	<!-- Here is where we loop through our $profiles array, printing out info -->

    <?php foreach ($profiles as $profile): ?>
    <tr>
	<td><?php echo $this->Html->link($profile['Profile']['firstname'],array('controller' => 'profiles',
			'action' => 'view', $profile['Profile']['id'])); ?></td>
        <td><?php echo $this->Html->link($profile['Profile']['lastname'],array('controller' => 'profiles',
			'action' => 'view', $profile['Profile']['id'])); ?></td>
        <td><?php echo $profile['Profile']['email']; ?></td>
        <td><?php echo $profile['Profile']['age']; ?></td>

	<td><?php if ($profile['Profile']['sex'] == 0) {
			echo $sexfemale;
		  }elseif ($profile['Profile']['sex'] == 1){
			echo $sexmale;} ?>
	</td>

        <td><?php echo $profile['Profile']['phone']; ?></td>

	<td><?php if ($profile['Profile']['smoker'] == 0) {
			echo $no;
		  }elseif ($profile['Profile']['smoker'] == 1){
			echo $yes;} ?>
	</td>

	<td><?php if ($profile['Profile']['pet'] == 0) {
			echo $no;
		  }elseif ($profile['Profile']['pet'] == 1){
			echo $yes;} ?>
	</td>

	<td><?php if ($profile['Profile']['child'] == 0) {
			echo $no;
		  }elseif ($profile['Profile']['child'] == 1){
			echo $yes;} ?>
	</td>

	<td><?php if ($profile['Profile']['couple'] == 0) {
			echo $no;
		  }elseif ($profile['Profile']['couple'] == 1){
			echo $yes;} ?>
	</td>

        <td><?php echo $profile['Profile']['max_roommates']; ?></td>

	<td><?php echo $this->Html->link('Διαγραφή', 
					 array('action' => 'delete', $profile['Profile']['id']),
					 null,
					 'Είστε βέβαιος για τη διαγραφή;') ?>
	    <?php echo $this->Html->link('Επεξεργασία', 
				         array('action' => 'edit', $profile['Profile']['id'])); ?> 
	</td>

    </tr>
    <?php endforeach; ?>
</table>
