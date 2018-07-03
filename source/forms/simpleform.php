<?php
// moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");
 
class simplehtml_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
 
        $mform = $this->_form;
        // Resource ID
        $mform->addElement('text', 'resourceid', 'Ressourcen-ID');
        $mform->setType('resourceid', PARAM_NOTAGS);
        $mform->setDefault('resourceid', '');

        // Defect
		$mform->addElement('textarea', 'schaden', 'Schaden');
		$mform->setType('schaden', PARAM_NOTAGS);
        $mform->setDefault('schaden', '');

        $mform->addElement('submit', 'btnSubmit', 'Schaden vermerken');
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}
