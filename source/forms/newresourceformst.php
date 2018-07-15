<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class newresourcesthtml_form extends moodleform {

    public function definition() {
        global $CFG;
        $mform = $this->_form; // Don't forget the underscore!

        /* ****************** Inventarnummer *************/
		$mform->addElement('text', 'invnr', 'Inventarnummer');
        $mform->setType('invnr', PARAM_NOTAGS);
        
        /* ****************** Seriennummer *************/
		$mform->addElement('text', 'sernr', 'Seriennummer');
        $mform->setType('sernr', PARAM_NOTAGS);
        
        /* ****************** Beschreibung *************/
		$mform->addElement('text', 'besch', 'Beschreibung');
        $mform->setType('besch', PARAM_NOTAGS);
        
        /* ****************** Kommentar *************/
		$mform->addElement('text', 'kom', 'Kommentar');
		$mform->setType('kom', PARAM_NOTAGS);

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

