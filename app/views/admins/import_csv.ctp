<div id='main-inner' class='mainImport'>
    <div id='import-cont' class='mainCenter'>
        <div class='import-title'>
            <h2>Εισαγωγή αρχείου επιτυχόντων στο ΤΕΙ Αθήνας</h2>
        </div>
        <br /><br /><p>
            Επιλέξτε το αρχείο (σε μορφή CSV) με τον κατάλογο των επιτυχόντων
            στο ΤΕΙ Αθήνας για το ερχόμενο ακαδημαϊκό έτος και πατήστε 'Εισαγωγή'
        </p>
        <div class='import-title'>
        <?php
            echo $this->Form->create('Admin', array(
                'type' => 'file'));
            echo $this->Form->file('Admin.submittedfile');
            echo "<br /><br />";
            echo $this->Form->submit('Εισαγωγή', array('class' => 'button'));
            echo $this->Form->end();
        ?>
        </div>
        <?php

            // display a report of the outcome and provide descriptions and help
            if (isset($report)) {
                $li = '<li class="import-report">';
                echo <<< REP
        <div>
            <br />
            <hr />
            <h2 class="search-title">Αναφορά</h2>
            <ul class="import-report">
                {$li}
                    <p class="report-stat">Πλήθος εγγραφών: {$report['total']}</p>
                    <p class="report-hint">
                        Το συνολικό πλήθος των εγγραφών που εντοπίστηκαν μέσα στο αρχείο.
                    </p>
                </li>
                {$li}
                    <p class="report-stat">Νέοι χρήστες: {$report['new']}</p>
                    <p class="report-hint">
                        Πλήθος λογαριασμών που δημιουργήθηκαν βάσει των εγγραφών του αρχείου.
                    </p>
                </li>
                {$li}
                    <p class="report-stat">Ήδη υπαρκτοί χρήστες: {$report['old']}</p>
                    <p class="report-hint">
                        Εγγραφές του αρχείου που αγνοήθηκαν επειδή ο ΑΜ τους ταυτίζεται με όνομα υπαρκτού χρήστη της υπηρεσίας.
                    <p>
                </li>
                {$li}
                    <p class="report-stat">Ελλιπή στοιχεία: {$report['bad']}<p>
                    <p class="report-hint">
                        Εγγραφές του αρχείου που αγνοήθηκαν λόγω ελλιπών στοιχείων.
                        Βεβαιωθείτε για την ορθότητα του αρχείου και προσπαθήστε πάλι.
                    </p>
                </li>
                {$li}
                    <p class="report-stat">Σφάλματα εκτέλεσης: {$report['fail']}</p>
                    <p class="report-hint">
                        Εγγραφές οι οποίες δεν κατέστη δυνατό να δημιουργήσουν νέους λογαριασμούς λόγω αποτυχίας του συστήματος.
                    </p>
                </li>
            </ul>
        </div>
REP;
            }
        ?>
    </div>
</div>
