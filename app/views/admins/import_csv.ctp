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
    <h2 class="search-title">Αναφορά</h2>
    <ul class="import-report">
        {$li}<p>Πλήθος εγγραφών που διαβάστηκαν: {$report['total']}</p></li>
        {$li}Χρήστες που δημιουργήθηκαν: {$report['new']}</li>
        {$li}Χρήστες που εντοπίστηκαν ότι υπάρχουν ήδη: {$report['old']}</li>
        {$li}Αγνοημένες εγγραφές λόγω ελλιπών στοιχείων: {$report['bad']}</li>
        {$li}Σφάλματα εκτέλεσης: {$report['fail']}</li>
    </ul>
REP;
            }
        ?>
    </div>
</div>
