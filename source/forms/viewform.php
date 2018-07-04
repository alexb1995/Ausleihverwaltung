<?php
// moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");
 
class viewhtml_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
 
        $mform = $this->_form;
        // Resource ID
        $mform->addElement('static', 'resourceid', 'Ressourcen-ID', $this->_customdata['resourceid']);
        // Input field for defect
		$mform->addElement('static', 'schaden', 'Schaden', $this->_customdata['schaden']);
        // Button to edit or delete the entry
        $btn = array();
        $btn[] =& $mform->createElement('submit', 'btnEdit', 'Vermerk bearbeiten');
        $btn[] =& $mform->createElement('submit', 'btnDelete', 'Vermerk löschen');
        $mform->addGroup($btn, 'btn', '', array(' '), false);
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}