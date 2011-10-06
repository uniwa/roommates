<div id='top-frame' class='frame'>
	<div class='frame-container'>
		<div id='top-photo'>
			<img src='./img/profile_avatar.png' />
		</div>
		<div id='top-title' class='title'>
			<h1>
			<?php
				$name = $profile['Profile']['firstname']." ".$profile['Profile']['lastname'];
				$age = $profile['Profile']['age'];
				$gender = ($profile['Profile']['gender'])?'γυναίκα':'άνδρας';
				$smoker = ($profile['Profile']['smoker'])?'ναι':'όχι';
				$pet = ($profile['Profile']['pet'])?'ναι':'όχι';
				$couple = ($profile['Profile']['couple'])?'ναι':'όχι';
				$child = ($profile['Profile']['child'])?'ναι':'όχι';
				echo Sanitize::html($name, array('remove' => true));
				
				function echoDetail($title, $option){
					$span = array("open" => "<span class='profile-strong'>", "close" => "</span>");
					echo $title.': '.$span['open'].$option.$span['close']."<br \>\n";
				}
			?>
			</h1>
		</div>
		<div id='main-details' class='options profile-big'>
			<?php echo $age; ?></span> ετών, <span class='profile-strong'>
			<?php echo $gender; ?></span><br />
			<?php
				echoDetail('Καπνιστής', $smoker);
				echoDetail('Κατοικίδιο', $pet);
				echoDetail('Παιδί', $child);
				echoDetail('Ζευγάρι', $couple);
			?></span>
		</div>
	</div>
</div>

<div id='bottom-frame' class='frame'>
	<div class='frame-container'>
		<div id='bottom-title' class='title'>
			<h2>Προτιμήσεις συγκατοίκων</h2>
		</div>
		<div id='bottom-subtitle' class='subtitle'>
		</div>
		<div id='profile-preferences' class='options profile-big'>
			Επιθυμητός αριθμός συγκατοίκων: <span class='profile-strong'>
			<?php
				$roommateswanted = $profile['Profile']['max_roommates'];
				echo Sanitize::html($roommateswanted, array('remove' => true))
			?></span>
			<br />Ηλικία: 
			<?php
				$age_min = $profile['Preference']['age_min'];
				$age_max = $profile['Preference']['age_max'];
				if($age_min){
			?>από <span class='profile-strong'><?php
					echo $profile['Preference']['age_min']." ";
				}
				if($age_max){
			?></span>μέχρι <span class='profile-strong'><?php
					echo $profile['Preference']['age_max'];
				}
			?></span><br />Φύλο: <span class='profile-strong'>
			<?php
				$prefgender = $profile['Preference']['pref_gender'];
				if($prefgender < 2){
					$prefgender = ($prefgender)?'άνδρες':'γυναίκες';
				}else{
					$prefgender = 'αδιάφορο';
				}
				echo $prefgender;
			?></span><br />Καπνιστής: <span class='profile-strong'>
			<?php
				$prefsmoker = $profile['Preference']['pref_smoker'];
				if($prefsmoker < 2){
					$prefsmoker = ($profile['Preference']['pref_smoker'])?'ναι':'όχι';
				}else{
					$prefsmoker = 'αδιάφορο';
				}
				echo $prefsmoker;
			?></span><br />Κατοικίδιο: <span class='profile-strong'>
			<?php
				$prefpet = $profile['Preference']['pref_pet'];
				if($prefpet < 2){
					$prefpet = ($profile['Preference']['pref_pet'])?'ναι':'όχι';
				}else{
					$prefpet = 'αδιάφορο';
				}
				echo $prefpet;
			?></span><br />Παιδί: <span class='profile-strong'>
			<?php
				$prefchild = $profile['Preference']['pref_child'];
				if($prefchild < 2){
					$prefchild = ($profile['Preference']['pref_child'])?'ναι':'όχι';
				}else{
					$prefchild = 'αδιάφορο';
				}
				echo $prefchild;
			?></span><br />Ζευγάρι: <span class='profile-strong'>
			<?php
				$prefcouple = $profile['Preference']['pref_couple'];
				if($prefcouple < 2){
					$prefcouple = ($profile['Preference']['pref_couple'])?'ναι':'όχι';
				}else{
					$prefcouple = 'αδιάφορο';
				}
				echo $prefcouple;
			?></span>
		</div>
	</div>
</div>

<?php // echo $this->Html->link("Προσθήκη Προφίλ", array('action' => 'add')); ?>
