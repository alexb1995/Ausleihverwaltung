<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class simplehtml_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('text', 'ausleiher', 'Ausleiher',$attributes='size=“100”');
        $mform->setType('ausleiher', PARAM_NOTAGS);
        $mform->setDefault('ausleiher', 'Bitte Namen eingeben');

        $mform->addElement('text', 'grund', 'Grund der Ausleihe'); // Add elements to your form
        $mform->setType('grund', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('grund', 'Bitte Grund eingeben');        //Default value

      $mform->addElement('date_selector', 'returnDate', 'Rückgabedatum');

        $mform->addElement('text', 'anmerkung', 'Anmerkung');
        $mform->setType('anmerkung', PARAM_NOTAGS);
        $mform->setDefault('anmerkung', 'Bitte geben Sie eine Anmerkung ein');


        $mform->addElement('checkbox', 'ratingtime',null, 'Laptop');
        $mform->addElement('checkbox', 'ratingtime',null, 'Tablet');
        $mform->addElement('checkbox', 'ratingtime',null, 'Smartphone');
        $mform->addElement('checkbox', 'ratingtime',null, 'Software');
        
        
        $mform->addElement('text', 'grund', null); // Add elements to your form
        $mform->setType('grund', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('grund', 'Filtern nach Tags');        //Default value
       


        $mform->addElement('text', 'anmerkung', null);
        $mform->setType('anmerkung', PARAM_NOTAGS);
        $mform->setDefault('anmerkung', null);

        $mform->addElement('text', 'deviceId', 'DeviceId');
        $mform->setType('deviceId', PARAM_NOTAGS);
        $mform->setDefault('deviceId', 'Bitte geben Sie die Geräte ID ein');


        // error_log("TEST FROM INSIDE FORM");

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('submit', 'btnSubmit', 'Weiter');

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('submit', 'btnBack', 'Zurück');

        // error_log("TEST FROM AFTER SUBMIT IN FORM");

    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}
