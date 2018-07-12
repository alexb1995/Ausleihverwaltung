<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class resourcehtml_form extends moodleform {

    public function definition() {
        global $CFG;
        $mform = $this->_form; // Don't forget the underscore!

        /* ****************** NAME *************/
		$mform->addElement('text', 'name', 'Name');
		$mform->setType('name', PARAM_NOTAGS);
        $mform->setDefault('name', $this->_customdata['name']);

        /* ****************** DESCRIPTION *************/
        $mform->addElement('text', 'description', 'Beschreibung'); // Add elements to your form
        $mform->setType('description', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('description', $this->_customdata['description']);        //Default value

        /* ****************** serialnumber *************/
        $mform->addElement('text', 'serialnumber', 'Seriennummer');
		$mform->setType('serialnumber', PARAM_NOTAGS);
        $mform->setDefault('serialnumber', $this->_customdata['serialnumber']);

        /* ****************** INVENTORYNUMBER *************/
        $mform->addElement('text', 'inventorynumber', 'Inventarnummer'); // Add elements to your form
        $mform->setType('inventorynumber', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('inventorynumber', $this->_customdata['inventorynumber']);        //Default value

        /* ****************** COMMENT *************/
        $mform->addElement('text', 'comment', 'Kommentar');
		$mform->setType('comment', PARAM_NOTAGS);
        $mform->setDefault('comment', $this->_customdata['comment']);

        /* ****************** STATUS *************/
        $mform->addElement('text', 'status', 'Status'); // Add elements to your form
        $mform->setType('status', PARAM_INT);                   //Set type of element
        $mform->setDefault('status', $this->_customdata['status']);        //Default value

        /* ****************** AMOUNT *************/
        $mform->addElement('text', 'amount','Menge');
		$mform->setType('amount', PARAM_INT);
        $mform->setDefault('amount', $this->_customdata['amount']);

        /* ****************** TYPE *************/
        $mform->addElement('text', 'type', 'Typ'); // Add elements to your form
        $mform->setType('type', PARAM_INT);                   //Set type of element
        $mform->setDefault('type', $this->_customdata['type']);        //Default value

        /* ****************** MAINCATEGORY *************/
        $mform->addElement('text', 'maincategory', 'Hauptkategorie');
		$mform->setType('maincategory', PARAM_NOTAGS);
        $mform->setDefault('maincategory', $this->_customdata['maincategory']);

        /* ****************** TYPE *************/
        $mform->addElement('text', 'subcategory', 'Subkategorie'); // Add elements to your form
        $mform->setType('subcategory', PARAM_NOTAGS);                   //Set type of element
        $mform->setDefault('subcategory', $this->_customdata['subcategory']);        //Default value

         /* ****************** DEFECT *************/
         $mform->addElement('text', 'defect', 'Schaden'); // Add elements to your form
         $mform->setType('defect', PARAM_NOTAGS);                   //Set type of element
         $mform->setDefault('defect', $this->_customdata['defect']);        //Default value



        error_log("TEST FROM INSIDE FORM");

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'resourceid');
        $mform->setType('resourceid', PARAM_INT);
      
        $mform->addElement('submit', 'btnSubmit', 'Speichern');

        error_log("TEST FROM AFTER SUBMIT IN FORM");

    }
    //Custom validation should be added here
    function validation($data, $files) {
   
        return array();
    }
}

