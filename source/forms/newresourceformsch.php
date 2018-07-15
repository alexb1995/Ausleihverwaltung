<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class newresourceschhtml_form extends moodleform {

    public function definition() {
        global $CFG;
        $mform = $this->_form; // Don't forget the underscore!

        /* ****************** Beschreibung *************/
		$mform->addElement('text', 'besch', 'Beschreibung');
        $mform->setType('besch', PARAM_NOTAGS);
        
        /* ****************** Anzahl *************/
		$mform->addElement('text', 'anz', 'Anzahl');
        $mform->setType('anz', PARAM_NOTAGS);

        error_log("TEST FROM INSIDE FORM");

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'resourceid');
        $mform->setType('resourceid', PARAM_INT);
      
        $mform->addElement('submit', 'btnNext', 'Weiter');

        error_log("TEST FROM AFTER SUBMIT IN FORM");

    }
    //Custom validation should be added here
    function validation($data, $files) {
   
        return array();
    }
}

