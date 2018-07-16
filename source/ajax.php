<?php
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'abbrechen':
            abbrechen();
            break;
        case 'bestaetigen':
            bestaetigen();
            break;
    }
}

function abbrechen() {
    echo "The abbrechen function is called.";
    exit;
}

function bestaetigen() {
    echo "The bestaetigen function is called.";
    exit;
}
?>