<div id='top-frame' class='frame'>
	<div class='frame-container'>
		<div id='top-photo'>
			<img src='./img/profile_avatar.png' />
		</div>
		<div id='top-title' class='title'>
			<h1>
			<?php
				$name = $profile['Profile']['firstname']." ".$profile['Profile']['lastname'];
				echo Sanitize::html($name, array('remove' => true));
			?>
			</h1>
		</div>
		<div id='main-details' class='options profile-big'>
			είμαι <span class='profile-strong'><?php
				$gender = ($profile['Profile']['gender'])?'γυναίκα':'άνδρας';
				echo $gender.", ".$profile['Profile']['age'];
			?></span> ετών<br />
			<span class='profile-strong'><?php
				$smoker = ($profile['Profile']['smoker'])?'':'δεν ';
				$smoker .= 'καπνίζω';
				$pet = ($profile['Profile']['pet'])?'':'δεν ';
				$pet .= 'έχω κατοικίδιο';
				echo $smoker;
			?></span>,<span class='profile-strong'><?php
				echo ' '.$pet;
			?></span><br />
			<span class='profile-strong'><?php
				$couple = ($profile['Profile']['couple'])?'':'δεν ';
				$couple .= 'συζώ';
				$child = ($profile['Profile']['child'])?'':'δεν ';
				$child .= 'έχω παιδί';
				echo $couple;
			?></span> και <span class='profile-strong'><?php
					echo $child;
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
			Επιθυμώ να συγκατοικήσω με <span class='profile-strong'><?php
				$roommateswanted = $profile['Profile']['max_roommates'];
				echo Sanitize::html($roommateswanted, array('remove' => true))
			?></span> άτομα.<br /><?php
				$age_min = $profile['Preference']['age_min'];
				$age_max = $profile['Preference']['age_max'];
                if($age_min || $age_max){
					echo 'ηλικίας ';
			?><span class='profile-strong'><?php
					$agepref = '';
					$agepref .= ($age_min)?'από '.$profile['Preference']['age_min']:'';
					$agepref .= ($age_max)?' μέχρι '.$profile['Preference']['age_max']:'';
				    echo Sanitize::html($agepref, array('remove' => true));
				}
			?></span> ετών,<br /><span class='profile-strong'><?php
				switch($profile['Preference']['pref_gender']){
					case 0:
						$preferredgender = 'άνδρες';
						break;
					case 1:
						$preferredgender = 'γυναίκες';
						break;
					default:
						$preferredgender = false;
						break;
				}
				if($preferredgender){
					echo $preferredgender;
				}
			?></span><span class='profile-strong'><?php
				if($profile['Preference']['pref_smoker'] < 2){
					$prefsmoker = ($profile['Preference']['pref_smoker'])?'μη':'';
					echo ', ';?><span class='profile-strong'><?php
					echo $prefsmoker.' καπνιστές';
				}
			?></span><?php
				if($profile['Preference']['pref_pet'] < 2){
					?>, <span class='profile-strong'><?php
					$prefpet = ($profile['Preference']['pref_pet'])?'χωρίς':'με';
					echo $prefpet.' κατοικίδιο';
				}
			?></span><?php
				if($profile['Preference']['pref_child'] < 2){
					?>, <br /><span class='profile-strong'><?php
					$prefchild = ($profile['Preference']['pref_child'])?'χωρίς':'με';
					echo $prefchild.' παιδί';
				}
			?></span><?php
				if($profile['Preference']['pref_couple'] < 2){
					?>, <span class='profile-strong'><?php
					$prefcouple = ($profile['Preference']['pref_couple'])?'δεν':'';
					echo 'που '.$prefcouple.' συζούν';
				}
			?></span>.
		</div>
	</div>
</div>

<?php // echo $this->Html->link("Προσθήκη Προφίλ", array('action' => 'add')); ?>
