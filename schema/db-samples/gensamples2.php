<?php
    define('NUM_USERS', 50);
    define('SAMPLE_PREFIX', 'insert-extra-');
    define('SAMPLE_EXTENTION', '.sql');
    $sampleField = array('users', 'profiles', 'houses');
    $genders = array('m', 'f');

    $first_names = array();

    $lines = file('names_m.txt');
    foreach ($lines as $line) {
         $first_names['m'][] = $line;
    }

    $lines = file('names_f.txt');
    foreach ($lines as $line) {
        $first_names['f'][] = $line;
    }

    $last_names = array();
    foreach ( range('A', 'Z') as $lastname) {
        $last_names[] = $lastname.'.';
    }


    $street_names = array('18 Άγγλων','25ης Αυγούστου','28ς Οκτωβρίου','62 Μαρτύρων','Αβέρωφ','Αγίας Αικατερίνης','Αγίου Τίτου','Αγίου Τίτου','Αγιοστεφανιτών','Αγίου Μηνά','Αγίων Δέκα','Αδριανού','Αθανασίου Διάκου','Αθηνάς','Αϊνικολιώτη','Ακαδημίας','Ακρωτηρίου','Αλκαίου','Αλμπερτ','Αλμυρού','Αμαλθείας','Αμαρίου','Αμισού','Ανδρέου Κρήτης','Αναγεννήσεως','Αναλήψεως','Ανδρόγεω','Ανδρου','Ανθεμίου','Ανωγείων','Ανωπόλεως','Αποκορώνου','Απολλωνίας','Αρβης','Αργυράκη','Αρετούσης','Αριάδνης','Αριστείδη','Αρκάδων','Αρκολεόντων','Αρσινόης','Αρχανών','Αρχιμήδη','Αρχοντοπούλου','Αστερουσίων','Ατρειδών','Αττάλου','Αυγέρη','Αυλώνος','ΑΧΕΠΑ','Bαλέστρα','Bαρνάβα','Bασιλογιώργη','Bάσσου','Βενιζέλου Ελευθ.','Βενιζέλου Σοφοκλή','Βιάννου','Βίγλας','Βικέλα','Βιστάκη','Βλαστών','Βοίβης','Βουρβάχηδων','Bουρδουμπάδων','Bυζαντίου','Βύρωνος','Γαβαλάδων','Γερωνυμάκη','Γεωργιάδη Γ.','Γεωργιάδη Τίτου','Γιαμαλάκη','Γιαμπουδή','Γιαμπουδή πάροδος','Γιάνναρη','Γιάνναρου','Γιαννίκου','Γιαννιτσών','Γιαννοπούλου Γ.','Γιούχτα','Γκερόλα','Γλυκοπούλου','Γοργολαϊνη','Γορτύνης','Γραμβούσης','Γρεβενών','Δαιδάλου','Δαιμονάκηδων','Δαμασκηνού','Δαμβέργηδων','Δαμιανού','Δασκαλογιάννη','Δεκελείας','Δελημάρκου','Δευκαλίωνος','Δημοκρατίας','Διατονίου','Δικαιοσύνης','Δικταίου Άντρου','Δίκτης','Δίκτύννης','Δοϊράνης','Δρακοντοπούλου','Δρετάκη','Δριμυτινού','Δωδεκανήσου','Δωριέων','Εθνικής Αντιστάσεως','Ελευθερίας','Ενδυμιώνος','Εξηκίου','Εκάτης','Ε.Ο.Κ.','Επικούρου','Επιμενίδου','Επιχάρμου','Επτανήσου','Ερωτοκρίτου','Ερωφίλης','Ευαγγελιστρίας','Ευρώπης','Εφέσου','Εφόδου','Ζαμπελίου','Ζαχαριουδάκη Εμμ.','Ζερβουδάκη','Ζουδιανού','Ζωγράφου','Zώτου ','Ηλέκτρας','Θαλή','Θεοτοκοπούλου','Θεοδοσίου Διακόνου','Θερίσσου','Θενών','Θεσσαλονίκης','Θησέως','Ιδαίου Άντρου','Ίδης','Ιδομενέως','Ικάρου','Ίμβρου','Ιουστινιανού','Ισιδώρου','Ισμήνης','Ιτάνου','Iωαννίνων','Ιωνίας','Καγιαμπή','Καζάνη','Καζαντζάκη Ν.','Kαινουρίου','Καλημεράκη','Καλογήρου','Καλογνώμωνος','Kαλοκαιρινού','Καλοκαιρινού Λυσ.','Καλονάδων','Καντανολεόντων','Καπετάνισσας Μαριώς','Καραγιάννη','Καρτερού','Κάσου','Καστρινάκη Εμμ.','Κατεχάκη Α.','Κισσάμου','Kνωσσού','Κοζύρη','Κοκκίνη','Koκκινίδη','Κονδυλάκη','Κοραή','Κοσμά Αιτωλού','Κόσμων','Koυνάλη','Κουντουριώτη Ναυάρχου','Koυρέντη Αν.','Koυρμούληδων','Koυρουλάκη E.','Κουρτικά','Κορνάρου','Κορωναίου','Κριτοβουλίδου','Κρόνου','Κυδωνίας','Κυκλάδων','Κύπρου','Κυράς της Ρω','Κυρίλλου Λουκάρεως ','Λασαίας','Λασθένους','Λασιθίου','Λαχανά','Λεβί','Λεράτου','Λεωνίδου','Λήμνου','Λογίου','Λυκούργου','Mακαρίου Αρχιεπισκόπου','Mακράκη Γ.','Mαλεβυζίου','Μαλικούτη','Μανδηλαρά Νικηφ.','Μανουσογιάννη','Μανωλεσάκη','Mαραθωνομάχων','Mαρινέλη','Mαρκοπούλου Ταξ.','Mαρογιώργη','Μαστραχά','Mαυρογένους Μ.','Μαυρολένης','Μάχης Κρήτης','Μελιδονίου','Μελιδώνη','Μελισσηνών','Μεραμβέλλου','Μηλιαρά','Μηλιαράκη Α.','Μήτσα','Μητσοτάκη Ι.','Μιλάτου','Μίνωος','Μινωταύρου','Μιχαήλ Αρχαγγέλου','Μιχελιδάκη','Μιχελιδόπαπα','Μονής Αγκαράθου','Μονής Βροντησίου','Moνής Γουβερνέτου','Moνής Γωνιάς','Moνής Επανωσήφη','Moνής Καρδιωτίσσης','Μονής Οδηγήτριας','Moνής Παλιανής','Moνής Πρέβελη','Moνής Τοπλού','Μονοφατσίου','Μουρέλου','Μπιζανίου','Μπουρλότου Σ.','Mποφώρ Δουκός','Μυλοποτάμου','Mυριόνου','Mύσωνος','Nικαίας','Νικολαϊδη Στ.','Nιώτη Αρ.','Nταλιάνη','Nτεντινάκηδων','Nτιλιντα','Ξανθουδίδη','Ξωπατέρα','Oικονόμου','Ουράνη Κ.','Παγγαίου','Παλμέτη','Πανασσανού','Πανδώρας','Παπαδοπούλου Κ.','Παπαγιαμαλή','Παπαλεξάνδρου','Παπανούτσου','Παρδάλη','Πάρου','Πασιφάης','Πατρός Αντωνίου','Παύλου Αποστ.','Παυσανίου','Πεζανού στρατ','Πελασγών','Περατζάκη','Πεδιάδος','Πεδιώτη Μιχ','Περδικάρη','Πετλεμπούρη','Πετράκη Λουκά','Πετροπουλάκη','Πηνειού','Πινδάρου','Πλαστήρα Ν','Πλάτωνος Ν.','Πλεύρη Τηλέμ.','Πολυχρονάκη','Πρεβελάκη Παντ.','Πρεκατσούνη','Πτολεμαίων','Πυργιωτίσσης','Πυράνθου','Ραδαμάνθυος','Ραύκου','Ρέας','Ρενιέρη','Ριανού','Ριζηνίας','Ρόδων','Ρολλέν','Ρωμανού','Σαββαθιανών','Σάθα','Σακουλιέρηδων','Σακορράφου','Σαπφούς','Σαρανταπόρου','Σγουρομαλλίνης','Σεμέλης','Σερβίλη','Σητείας','Σινά','Σκορδύλων','Σκουλάδων','Σκουλούδη Στ.','Σκρα','Σμύρνης','Σουμερλή','Σορβόλου','Σοφοκλή','Σπιναλόγγας','Σταυράκη Ν.','Στεργιογιάννη','Συμιακού','Σύρου','Σφακιανάκη','Σφακίων','Τάλω','Τάρρας','Τενέδου','Τζουλάκη Ταγμ.','Τζουμαγιάς','Tήνου','Tομπάζη','Τριφύτσου','Τρικούπη Χαριλ.','Τσακίρη','Τσικριτζή','Τσιριντάνηδων','Τσουδερών','Tυλίσσου','Tυρνάβου','Tυρταίου','Υγείας','Φαίδρας','Φαϊτάκη','Φωτίου','Xαϊνηδων','Χάληδων','Χάλκηδόνος','Χάνδακος','Χανίων','Χαιρέτηδων','Xάουζ Σαμουήλ','Xαραλάμπη','Xαρκούτση','Χατζηδάκη Ι.','Xατζηκοκόλη','Xερσονήσου','Χορτατσών','Χούρδου Ρ.','Χρονάκη Γ. Χρυσοστόμου Αρχιεπισκ.','Ψαρομηλίγγων','Ψυλλάκη Βασ. Ψυχάρη');

    $streets = array('οδός','λεωφ.','πλατεία');

    $fu = openFile($sampleField[0]);
    $fp = openFile($sampleField[1]);
    $fh = openFile($sampleField[2]);

    for($i = 0; $i < NUM_USERS; $i++){

        // master ID same for all tables
        // +100 id offset
        $uid = 100 + $i;

        // -------------------
        // user table
        // -------------------

        //username
        $uname = "user".$i;
        $passwd = '8f9bc2b8007a93584efdf303b83619f1fc147016';
        $role = 'user';
        $terms_accepted = 1;

        // these are default at mysql
        //$banned = 0;
        //$enabled = 1;

        // -------------------
        // profiles table
        // -------------------

        $gender = rand(0,1);
        $fname = $first_names[$genders[$gender]][array_rand($first_names[$genders[$gender]])];
        $lname = $last_names[array_rand($last_names)];
        $email = 'tlatsas@edu.teiath.gr';
        $dob = rand(1993, 1973);
        $phone = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
        $smoker = rand(0,2);
        $pet = rand(0,2);
        $child = rand(0,2);
        $couple = rand(0,2);
        $we_are = rand(1,4);
        $max_roommates = rand(1,9);
        $visible = 1;
        $get_mail = 1;
        $token = sha1(uniqid($uname, true));

        // -------------------
        // houses table
        // -------------------

        $street = $streets[array_rand($streets)];
        $address = $street.' '.$street_names[array_rand($street_names)];
        $address = addslashes($address);
        $postal = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);

        $area = rand(20, 150);
        $bedroom_num = rand(1,3);
        $bathroom_num = rand(1,3);
        $price = rand(3*$area, 6*$area);
        $construction = rand(1900, 2011);
        $totalplaces = rand($we_are + 1, 9);
        $municipality_id = rand(1,30);
        // visibility always 1, get from profiles table variable
        $accessible = rand(0,1); // disability_facilities

        $solar = rand(0,1);
        $furnitured = rand(0,1);
        $aircondition = rand(0,1);
        $parking = rand(0,1);

        $floor_id = rand(1,5);

        // INSERT USER
        $insertUser = "INSERT INTO `roommates`.`users` (`id`, `username`, `password`, `role`, `terms_accepted`)\n";
        $insertUser .= "VALUES ('{$uid}', '{$uname}', '{$passwd}', '{$role}', '{$terms_accepted}');\n\n";

        // INSERT PROFILE
        $insertProfile = "INSERT INTO `roommates`.`profiles` ";
        $insertProfile .= "(`id`,`firstname`,`lastname`, `email`, `dob`,
            `gender`,`phone`,`smoker`,`pet`, `child`, `couple`,`we_are`,
            `max_roommates`,`visible`, `get_mail`, `token`, `created`,`modified`,`user_id`)\n";

        $insertProfile .= "VALUES ('{$uid}', '{$fname}', '{$lname}', '{$email}', '{$dob}',
            '{$gender}', '{$phone}', '{$smoker}', '{$pet}', '{$child}', '{$couple}', '{$we_are}',
            '{$max_roommates}', '{$visible}', '{$get_mail}', '{$token}', NOW(), NOW(), '{$uid}');\n\n";

        // INSERT HOUSE
        $insertHouse = "INSERT INTO `roommates`.`houses` ";
        $insertHouse .= "(`id`,`address`,`postal_code`,`area`,
            `bedroom_num`,`bathroom_num`,`price`,
            `construction_year`,`solar_heater`,`furnitured`,`aircondition`,`garden`,
            `parking`,`shared_pay`,`security_doors`,`disability_facilities`,`storeroom`,
            `availability_date`,`rent_period`,`description`,`created`,`modified`,`floor_id`,
            `house_type_id`,`heating_type_id`,`currently_hosting`,`total_places`,
            `user_id`,`municipality_id`,`visible`)\n";

        $insertHouse .= "VALUES ('{$uid}', '{$address}', '{$postal}', '{$area}',
            '{$bedroom_num}', '{$bathroom_num}', '{$price}',
            '{$construction}','{$solar}', '{$furnitured}', '{$aircondition}',0,
            '{$parking}',1,0,'{$accessible}',0,
            '2011-12-08',NULL,'',NOW(),NOW(), '{$floor_id}',
            2,2,'{$we_are}','{$totalplaces}',
            '{$uid}', '{$municipality_id}','{$visible}');\n\n";

        writeFile($fu, $insertUser);
        writeFile($fp, $insertProfile);
        writeFile($fh, $insertHouse);
    }
    
    closeFile($fu);
    closeFile($fp);
    closeFile($fh);

    function openFile($name){
        $incFile = SAMPLE_PREFIX.$name.SAMPLE_EXTENTION;
        if($fh = fopen($incFile, 'w')){
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