<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class newresourcehtml_form extends moodleform {

    public function definition() {
        global $CFG;
        $mform = $this->_form; // Don't forget the underscore!

        /* ****************** NAME *************/
		$mform->addElement('text', 'name', 'Name');
		$mform->setType('name', PARAM_NOTAGS);
        $mform->setDefault('name', $this->_customdata['name']);

        //Dropwdown-Menü wird erstellt, um Hauptkategorie auszuwählen
        $mform->addElement('select', 'category',
            get_string('category'), array('Handy', 'Tablet', 'Laptop', 'Computer', 'Software', 'Drucker', 'Kabel'));
        $mform->setType('category', PARAM_ALPHA);

        //Dropwdown-Menü wird erstellt, um ein oder mehrere Tag(s) auszuwählen
        $select = $mform->addElement('select', 'tags',
            get_string('tags'), array('iPhone', 'Convertible', 'Mac', 'Huawei', 'Nexus', 'LTE'));
        $select->setMultiple(true);    
        $mform->setType('tags', PARAM_ALPHA);

        //Radiobuton um Stückgut oder Schüttgut auszuwählen
        $radioarray = array();
        $radioarray[] = $mform->createElement('radio', 'Typ', '', get_string('Stückgut'), 1);
        $radioarray[] = $mform->createElement('radio', 'Typ', '', get_string('Schüttgut'), 0);
        $mform->addGroup($radioarray, 'Ressourcentyp', 'Ressourcentyp', array(' '), false);

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
