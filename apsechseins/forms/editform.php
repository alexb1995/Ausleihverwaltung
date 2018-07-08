<?php
// moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class edithtml_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;

        $mform = $this->_form;
        // Resource ID (static)
        $mform->addElement('static', 'resourceid', 'Ressourcen-ID', $this->_customdata['resourceid']);
        // Defect (can be edited)
        $mform->addElement('textarea', 'defect', 'Schaden');
        $mform->setType('defect', PARAM_NOTAGS);                
        $mform->setDefault('defect', $this->_customdata['defect']);
        $buttonarray = array();
        $buttonarray[] =& $mform->createElement('submit', 'btnSubmit', 'Speichern');
        $buttonarray[] =& $mform->createElement('cancel', 'btnCancel', 'Abbrechen');
        $mform->addGroup($buttonarray, 'btnArray', '', array(''), false);
    }

    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}