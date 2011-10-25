<?php
	define('NUM_USERS', 128);
	define('SAMPLE_PREFIX', 'insert-extra-');
	define('SAMPLE_EXTENTION', '.sql');
	$sampleField = array('users', 'profiles', 'houses');
	$genders = array('m', 'f');
	$first_names['m'] = array('Michael','Christopher','David','James','John','Robert','Brian','Matthew','Joseph','Daniel','William','Kevin','Joshua','Jeremy','Ryan','Eric','Timothy','Jeffrey','Richard');
	$first_names['f'] = array('Jennifer','Amy','Jessica','Heather','Angela','Michelle','Kimberly','Amanda','Kelly','Sarah','Lisa','Elizabeth','Stephanie','Nicole','Christina','Rebecca','Jamie','Shannon','Laura');
	$last_names = array('Temuujin','Ariunbold','Chinbat','Tsogt','Zaya','Lkhagvasuren','Mandukhai','Batsaikhan','Munkhbold','Bilguun','Bayarsaikhan','Ankhbayar','Gantumur','Bayarmaa','Byambadorj');
	$street_names = array('116','1131','1770','18 Άγγλων','1821','1866','1878','25ης Αυγούστου','28ς Οκτωβρίου','62 Μαρτύρων','Αβέρωφ','Αγίας Αικατερίνης','Αγίου Τίτου','Αγίου Τίτου','Αγιοστεφανιτών','Αγίου Μηνά','Αγίων Δέκα','Αδριανού','Αθανασίου Διάκου','Αθηνάς','Αϊνικολιώτη','Ακαδημίας','Ακρωτηρίου','Αλκαίου','Αλμπερτ','Αλμυρού','Αμαλθείας','Αμαρίου','Αμισού','Ανδρέου Κρήτης','Αναγεννήσεως','Αναλήψεως','Ανδρόγεω','Ανδρου','Ανθεμίου','Ανωγείων','Ανωπόλεως','Αποκορώνου','Απολλωνίας','Αρβης','Αργυράκη','Αρετούσης','Αριάδνης','Αριστείδη','Αρκάδων','Αρκολεόντων','Αρσινόης','Αρχανών','Αρχιμήδη','Αρχοντοπούλου','Αστερουσίων','Ατρειδών','Αττάλου','Αυγέρη','Αυλώνος','ΑΧΕΠΑ','Bαλέστρα','Bαρνάβα','Bασιλογιώργη','Bάσσου','Βενιζέλου Ελευθ.','Βενιζέλου Σοφοκλή','Βιάννου','Βίγλας','Βικέλα','Βιστάκη','Βλαστών','Βοίβης','Βουρβάχηδων','Bουρδουμπάδων','Bυζαντίου','Βύρωνος','Γαβαλάδων','Γερωνυμάκη','Γεωργιάδη Γ.','Γεωργιάδη Τίτου','Γιαμαλάκη','Γιαμπουδή','Γιαμπουδή πάροδος','Γιάνναρη','Γιάνναρου','Γιαννίκου','Γιαννιτσών','Γιαννοπούλου Γ.','Γιούχτα','Γκερόλα','Γλυκοπούλου','Γοργολαϊνη','Γορτύνης','Γραμβούσης','Γρεβενών','Δαιδάλου','Δαιμονάκηδων','Δαμασκηνού','Δαμβέργηδων','Δαμιανού','Δασκαλογιάννη','Δεκελείας','Δελημάρκου','Δευκαλίωνος','Δημοκρατίας','Διατονίου','Δικαιοσύνης','Δικταίου Άντρου','Δίκτης','Δίκτύννης','Δοϊράνης','Δρακοντοπούλου','Δρετάκη','Δριμυτινού','Δωδεκανήσου','Δωριέων','Εθνικής Αντιστάσεως','Ελευθερίας','Ενδυμιώνος','Εξηκίου','Εκάτης','Ε.Ο.Κ.','Επικούρου','Επιμενίδου','Επιχάρμου','Επτανήσου','Ερωτοκρίτου','Ερωφίλης','Ευαγγελιστρίας','Ευρώπης','Εφέσου','Εφόδου','Ζαμπελίου','Ζαχαριουδάκη Εμμ.','Ζερβουδάκη','Ζουδιανού','Ζωγράφου','Zώτου ','Ηλέκτρας','Θαλή','Θεοτοκοπούλου','Θεοδοσίου Διακόνου','Θερίσσου','Θενών','Θεσσαλονίκης','Θησέως','Ιδαίου Άντρου','Ίδης','Ιδομενέως','Ικάρου','Ίμβρου','Ιουστινιανού','Ισιδώρου','Ισμήνης','Ιτάνου','Iωαννίνων','Ιωνίας','Καγιαμπή','Καζάνη','Καζαντζάκη Ν.','Kαινουρίου','Καλημεράκη','Καλογήρου','Καλογνώμωνος','Kαλοκαιρινού','Καλοκαιρινού Λυσ.','Καλονάδων','Καντανολεόντων','Καπετάνισσας Μαριώς','Καραγιάννη','Καρτερού','Κάσου','Καστρινάκη Εμμ.','Κατεχάκη Α.','Κισσάμου','Kνωσσού','Κοζύρη','Κοκκίνη','Koκκινίδη','Κονδυλάκη','Κοραή','Κοσμά Αιτωλού','Κόσμων','Koυνάλη','Κουντουριώτη Ναυάρχου','Koυρέντη Αν.','Koυρμούληδων','Koυρουλάκη E.','Κουρτικά','Κορνάρου','Κορωναίου','Κριτοβουλίδου','Κρόνου','Κυδωνίας','Κυκλάδων','Κύπρου','Κυράς της Ρω','Κυρίλλου Λουκάρεως ','Λασαίας','Λασθένους','Λασιθίου','Λαχανά','Λεβί','Λεράτου','Λεωνίδου','Λήμνου','Λογίου','Λυκούργου','Mακαρίου Αρχιεπισκόπου','Mακράκη Γ.','Mαλεβυζίου','Μαλικούτη','Μανδηλαρά Νικηφ.','Μανουσογιάννη','Μανωλεσάκη','Mαραθωνομάχων','Mαρινέλη','Mαρκοπούλου Ταξ.','Mαρογιώργη','Μαστραχά','Mαυρογένους Μ.','Μαυρολένης','Μάχης Κρήτης','Μελιδονίου','Μελιδώνη','Μελισσηνών','Μεραμβέλλου','Μηλιαρά','Μηλιαράκη Α.','Μήτσα','Μητσοτάκη Ι.','Μιλάτου','Μίνωος','Μινωταύρου','Μιχαήλ Αρχαγγέλου','Μιχελιδάκη','Μιχελιδόπαπα','Μονής Αγκαράθου','Μονής Βροντησίου','Moνής Γουβερνέτου','Moνής Γωνιάς','Moνής Επανωσήφη','Moνής Καρδιωτίσσης','Μονής Οδηγήτριας','Moνής Παλιανής','Moνής Πρέβελη','Moνής Τοπλού','Μονοφατσίου','Μουρέλου','Μπιζανίου','Μπουρλότου Σ.','Mποφώρ Δουκός','Μυλοποτάμου','Mυριόνου','Mύσωνος','Nικαίας','Νικολαϊδη Στ.','Nιώτη Αρ.','Nταλιάνη','Nτεντινάκηδων','Nτιλιντα','Ξανθουδίδη','Ξωπατέρα','Oικονόμου','Ουράνη Κ.','Παγγαίου','Παλμέτη','Πανασσανού','Πανδώρας','Παπαδοπούλου Κ.','Παπαγιαμαλή','Παπαλεξάνδρου','Παπανούτσου','Παρδάλη','Πάρου','Πασιφάης','Πατρός Αντωνίου','Παύλου Αποστ.','Παυσανίου','Πεζανού στρατ','Πελασγών','Περατζάκη','Πεδιάδος','Πεδιώτη Μιχ','Περδικάρη','Πετλεμπούρη','Πετράκη Λουκά','Πετροπουλάκη','Πηνειού','Πινδάρου','Πλαστήρα Ν','Πλάτωνος Ν.','Πλεύρη Τηλέμ.','Πολυχρονάκη','Πρεβελάκη Παντ.','Πρεκατσούνη','Πτολεμαίων','Πυργιωτίσσης','Πυράνθου','Ραδαμάνθυος','Ραύκου','Ρέας','Ρενιέρη','Ριανού','Ριζηνίας','Ρόδων','Ρολλέν','Ρωμανού','Σαββαθιανών','Σάθα','Σακουλιέρηδων','Σακορράφου','Σαπφούς','Σαρανταπόρου','Σγουρομαλλίνης','Σεμέλης','Σερβίλη','Σητείας','Σινά','Σκορδύλων','Σκουλάδων','Σκουλούδη Στ.','Σκρα','Σμύρνης','Σουμερλή','Σορβόλου','Σοφοκλή','Σπιναλόγγας','Σταυράκη Ν.','Στεργιογιάννη','Συμιακού','Σύρου','Σφακιανάκη','Σφακίων','Τάλω','Τάρρας','Τενέδου','Τζουλάκη Ταγμ.','Τζουμαγιάς','Tήνου','Tομπάζη','Τριφύτσου','Τρικούπη Χαριλ.','Τσακίρη','Τσικριτζή','Τσιριντάνηδων','Τσουδερών','Tυλίσσου','Tυρνάβου','Tυρταίου','Υγείας','Φαίδρας','Φαϊτάκη','Φωτίου','Xαϊνηδων','Χάληδων','Χάλκηδόνος','Χάνδακος','Χανίων','Χαιρέτηδων','Xάουζ Σαμουήλ','Xαραλάμπη','Xαρκούτση','Χατζηδάκη Ι.','Xατζηκοκόλη','Xερσονήσου','Χορτατσών','Χούρδου Ρ.','Χρονάκη Γ. Χρυσοστόμου Αρχιεπισκ.','Ψαρομηλίγγων','Ψυλλάκη Βασ. Ψυχάρη');
	$streets = array('οδός','λεωφ.','πλατεία');
	$rangemax = NUM_USERS + 100;
	$profiles = range(101, $rangemax);
	$preferences = $profiles;
	$houses = $profiles;
	shuffle($profiles);
	shuffle($preferences);
	shuffle($houses);
	$fu = openFile($sampleField[0]);
	$fp = openFile($sampleField[1]);
	$fh = openFile($sampleField[2]);

	for($i = 0; $i < NUM_USERS; $i++){
		$gender = rand(0,1);
		$fname = $first_names[$genders[$gender]][array_rand($first_names[$genders[$gender]])];
		$lname = $last_names[array_rand($last_names)];
		$uname = strtolower(substr($fname,0,1).$lname).$i;
		$passwd = '8f9bc2b8007a93584efdf303b83619f1fc147016';
		$uid = 100 + $i;
		$street = $streets[array_rand($streets)];
		$address = mysql_real_escape_string($street.' '.$street_names[array_rand($street_names)]);
		$postal = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		$area = rand(20, 150);
		$beds = rand(1,3);
		$baths = rand(1,3);
		$price = rand(3*$area, 6*$area);
		$prefgender = rand(0,2);
		$prefsmoker = rand(0,2);
		$prefpet = rand(0,2);
		$prefchild = rand(0,2);
		$prefcouple = rand(0,2);
		$smoker = rand(0,2);
		$pet = rand(0,2);
		$child = rand(0,2);
		$couple = rand(0,2);
		$weare = rand(1,4);
		$totalplaces = rand($weare + 1, 9);
		$dob = rand(1993, 1973);
		$email = $uname.'@email.com';
		$phone = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		$munid = rand(1,30);
		$maxmates = rand(1,9);
		$pid = $profiles[$i];
		$prefid = $preferences[$i];
		$hid = $houses[$i];
		$visible = rand(0,1);
		$agemin = rand(18,34);
		$agemax = rand($agemin, 35);
		$accessible = rand(0,1);
		$construction = rand(1900, 2011);
		
		$insertUser = "INSERT INTO `roommates`.`users` (`id`, `username`, `password`, `role`)\n";			
		$insertUser .= "VALUES ('{$uid}', '{$uname}', '{$passwd}', 'user');\n\n";
		$insertProfile = "INSERT INTO `roommates`.`profiles` (`id`,`firstname`,`lastname`, `email`, `dob`, `gender`,`phone`,`smoker`,`pet`, `child`, `couple`,`we_are`,`max_roommates`,`visible`,`created`,`modified`,`preference_id`,`user_id`)\n";
		$insertProfile .= "VALUES ('{$pid}', '{$fname}', '{$lname}', '{$email}', '{$dob}', '{$gender}', '{$phone}', '{$smoker}', '{$pet}', '{$child}', '{$couple}', '{$weare}', '{$maxmates}', '{$visible}',NOW(),NOW(),'{$prefid}', '{$uid}');\n\n";
		$insertPreference = "INSERT INTO `roommates`.`preferences` (
		    `id`,`age_min`,`age_max`,`pref_gender`,`pref_smoker`,`pref_pet`,
		    `pref_child`,`pref_couple`, `price_min`, `price_max`, `area_min`,
		    `area_max`, `pref_municipality`, `bedroom_num_min`, `bathroom_num_min`,
		    `construction_year_min`, `pref_solar_heater`, `pref_furnitured`,
		    `pref_aircondition`, `pref_garden`, `pref_parking`, `pref_shared_pay`,
		    `pref_security_doors`, `pref_disability_facilities`, `pref_storeroom`,
		    `availability_date_min`, `rent_period_min`, `floor_id_min`, 
		    `pref_house_type_id`, `pref_heating_type_id`)\n";
		$insertPreference .= "VALUES ('{$prefid}', '{$agemin}', '{$agemax}', '{$prefgender}', '{$prefsmoker}', '{$prefpet}', '{$prefchild}', '{$prefcouple}',
		    0, 9999, 0, 9999, '', 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, NOW(), 0, 0, 0, 0);\n\n";
		$insertHouse = "INSERT INTO `roommates`.`houses` (`id`,`address`,`postal_code`,`area`,`bedroom_num`,`bathroom_num`,`price`,`construction_year`,`solar_heater`,`furnitured`,`aircondition`,`garden`,`parking`,`shared_pay`,`security_doors`,`disability_facilities`,`storeroom`,`availability_date`,`rent_period`,`description`,`created`,`modified`,`floor_id`,`house_type_id`,`heating_type_id`,`currently_hosting`,`total_places`,`user_id`,`municipality_id`)\n";
		$insertHouse .= "VALUES ('{$hid}', '{$address}', '{$postal}', '{$area}', '{$beds}', '{$baths}', '{$price}','{$construction}',1,1,1,0,0,1,0,'{$accessible}',0,'2011-11-05',NULL,'',NOW(),NOW(),3,2,2,'{$weare}', '{$totalplaces}', '{$uid}', '{$munid}');\n\n";
		
		writeFile($fu, $insertUser);
		writeFile($fp, $insertPreference.$insertProfile);
		writeFile($fh, $insertHouse);
	}
	
	closeFile($fu);
	closeFile($fp);
	closeFile($fh);

	function openFile($name){
		$incFile = SAMPLE_PREFIX.$name.SAMPLE_EXTENTION;
		if($fh = fopen($incFile, 'a')){
			return $fh;
		}else{
			return false;
		}
	}

	function writeFile($f, $data){
		fwrite($f, $data);
	}

	function closeFile($f){
		fclose($f);
	}

?>
