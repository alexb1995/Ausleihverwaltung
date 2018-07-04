<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class newhtml_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;

        $mform = $this->_form; // Don't forget the underscore!
        // Resource ID (static)
        $mform->addElement('static', 'resourceid', 'Ressourcen-ID', $this->_customdata['resourceid']);
        // Defect (can be edited)
        $mform->addElement('textarea', 'schaden', 'Schaden');
        $mform->setType('schaden', PARAM_NOTAGS);                
        $mform->setDefault('schaden', '');      
        // Buttongroup to save or cancel the changes
        $btn = array();
        $btn[] =& $mform->createElement('submit', 'btnSave', 'Schadensvermerk speichern');
        $btn[] =& $mform->createElement('cancel', 'btnCancel', 'Schadensvermerk verwerfen');
        $mform->addGroup($btn, 'btn', '', array(' '), false);
    }

    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}