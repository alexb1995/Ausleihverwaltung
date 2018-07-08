<?php
// moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");
 
class viewhtml_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
 
        $mform = $this->_form;
        // Resource ID (static)
        $mform->addElement('static', 'resourceid', 'Ressourcen-ID', $this->_customdata['resourceid']);
        // Defect (static)
		$mform->addElement('static', 'defect', 'Schaden', $this->_customdata['defect']);
    }

    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}