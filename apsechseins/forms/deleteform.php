<?php
// moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class edithtml_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!
        // Resource ID (static)
        $mform->addElement('static', 'resourceid', 'Ressourcen-ID', $this->_customdata['resourceid']);
        // Defect (can be edited)
        $mform->addElement('textarea', 'defect', 'Schaden');
        $mform->setType('defect', PARAM_NOTAGS);                
        $mform->setDefault('defect', $this->_customdata['defect']);      
        // Buttongroup to save or cancel the changes
        $btn = array();
       // $btn[] =& $mform->createElement('submit', 'btnSave', 'Änderungen speichern');
       // $btn[] =& $mform->createElement('cancel', 'btnCancel', 'Änderungen verwerfen');
        $mform->addGroup($btn, 'btn', '', array(' '), false);
    }

    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}