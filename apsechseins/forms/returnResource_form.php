<?php
// moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class returnResource_form extends moodleform {
    public function definition() {
        global $CFG;

        $mform = $this->_form;   

        /**************** ID ***************/
        $mform->addElement('static', 'text', 'ID', $this->_customdata['resourceid']);
        $mform->setType('resourceid', PARAM_INT);

        /************** Name ***************/
        $mform->addElement('static', 'text', 'Name', $this->_customdata['name']);
        $mform->setType('name', PARAM_NOTAGS);
       
        /************* Schaden **************/
        $mform->addElement('static', 'text', 'Schaden', $this->_customdata['defect']);
        $mform->setType('defect', PARAM_NOTAGS);

        /************* Status **************/
        $mform->addElement('select', 'available', 'Ressource wieder zur Ausleihe verfügbar', array('Ja', 'Nein'));

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('hidden', 'resourceid');
        $mform->setType('resourceid', PARAM_INT);

        $mform->addElement('submit', 'btnSubmit', 'Rückgabe verbuchen');
    }

    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}
