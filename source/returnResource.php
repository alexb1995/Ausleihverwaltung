<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of ausleihverwaltung
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_ausleihverwaltung
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... ausleihverwaltung instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('ausleihverwaltung', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $ausleihverwaltung  = $DB->get_record('ausleihverwaltung', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $ausleihverwaltung  = $DB->get_record('ausleihverwaltung', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $ausleihverwaltung->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('ausleihverwaltung', $ausleihverwaltung->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_ausleihverwaltung\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $ausleihverwaltung);
$event->trigger();

// Print the page header.
$PAGE->set_url('/mod/ausleihverwaltung/returnResource.php', array('id'=>$cm->id, 'resourceid'=>$_GET['resourceid']));
$PAGE->set_title(format_string($ausleihverwaltung->name));
$PAGE->set_heading(format_string($course->fullname));

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($ausleihverwaltung->intro) {
    echo $OUTPUT->box(format_module_intro('ausleihverwaltung', $ausleihverwaltung, $cm->id), 'generalbox mod_introbox', 'ausleihverwaltungintro');
}

// Replace the following lines with you own code.
echo $OUTPUT->heading('Schadensdokumentation');

/************************* CODE FOR SCHADENSDOKUMENTATION *************************/

$url = $PAGE->url;
$strUrl = $url.'';
require_once(dirname(__FILE__).'/forms/returnResource_form.php');

if(strpos($strUrl, 'resourceid=')){
    // First run
    $resourceid = $_GET['resourceid'];
    $resource = $DB->get_record('ausleihverwaltung_resources', array('id'=>$resourceid));
    $resourcename = $resource->name;
    $resourcedefect = $resource->defect;

    echo 'Möchten Sie die Rückgabe für die Ressource mit dem Namen ' . $resourcename . ' und der Ressourcen-ID ' . $resourceid . ' verbuchen?';

    // Initialize form and preallocate values
    require_once(dirname(__FILE__).'/forms/returnResource_form.php');
    $mform = new returnResource_form (null, array('resourceid'=>$resourceid, 'name'=>$resourcename, 'defect'=>$resourcedefect, 'status'=>''));

    if ($fromform = $mform->get_data()) {
        $fm_resourceid = $fromform->resourceid;

        switch ($fromform->available) {
            case 0:
                // Verfügbar
                $fm_status = 1;
            break;
            case 1:
                // Nicht verfügbar
                $fm_status = 2;
            break;
        };

        $record = new stdClass();
        $record->id                 = $fm_resourceid;
        $record->name               = $resource->name;
        $record->description        = $resource->description;
        $record->serialnumber       = $resource->serialnumber;
        $record->inventorynumber    = $resource->inventorynumber ;
        $record->comment            = $resource->comment;
        $record->status             = $fm_status;
        $record->amount             = $resource->amount;
        $record->type               = $resource->type;
        $record->maincategory       = $resource->maincategory;
        $record->subcategory        = $resource->subcategory;
        $record->defect             = $fm_resourcedefect;
    } else {
        $formdata = array('id'=>$id, 'resourceid'=>$resourceid);
        $mform->set_data($formdata);
        $mform->display();
    };
    echo nl2br("\n");
    // Navigation
    echo html_writer::link(new moodle_url('../ausleihverwaltung/saveDefect.php', array('id'=>$cm->id, 'resourceid'=>$resourceid)), 'Zurück: Schaden vermerken', array('class'=>'btn btn-secondary'));
    echo html_writer::link(new moodle_url('/course/view.php', array('id'=>2)), 'Weiter: Home', array('class'=>'btn btn-secondary'));
} else {
    // Second run
    require_once(dirname(__FILE__).'/forms/returnResource_form.php');
    $mform = new returnResource_form ();
    if ($fromform = $mform->get_data()) {
        $fm_resourceid = $fromform->resourceid;

        $resource = $DB->get_record('ausleihverwaltung_resources', array('id'=>$fm_resourceid));

        switch ($fromform->available) {
            case 0:
                // Verfügbar
                $fm_status = 1;
            break;
            case 1:
                // Nicht verfügbar
                $fm_status = 2;
            break;
        };

        $record = new stdClass();
        $record->id                 = $fm_resourceid;
        $record->name               = $resource->name;
        $record->description        = $resource->description;
        $record->serialnumber       = $resource->serialnumber;
        $record->inventorynumber    = $resource->inventorynumber ;
        $record->comment            = $resource->comment;
        $record->status             = $fm_status;
        $record->amount             = $resource->amount;
        $record->type               = $resource->type;
        $record->maincategory       = $resource->maincategory;
        $record->subcategory        = $resource->subcategory;
        $record->defect             = $resource->defect;

        $DB->update_record('ausleihverwaltung_resources', $record, $bulk=false);

        $ausleihantrag = $DB->get_record('ausleihverwaltung_borrowed', array('resourceid'=>$fm_resourceid));
        $record = new stdClass();
        $record->id                     = $ausleihantrag->id;
        $record->duedate                = $ausleihantrag->record;
        $record->resourceid             = $fm_resourceid;
        $record->studentmatrikelnummer  = $ausleihantrag->studentmatrikelnummer;
        $record->studentmailaddress     = $ausleihantrag->studentmailaddress;
        $record->borrowdate             = $ausleihantrag->borrowdate;
        $record->studentname            = $ausleihantrag->studentname;
        $record->borrowreason           = $ausleihantrag->borrowreason;
        $record->comment                = $ausleihantrag->borrowreason;
        $record->accepted               = $ausleihantrag->accepted;
        $record->returned               = 'true';

        $DB->update_record('ausleihverwaltung_borrowed', array('id'=>$ausleihantrag->id));

        echo 'Die Rückgabe der Ressource wurde verbucht.';
        echo nl2br("\n");
    } else {
        $formdata = array('id'=>$id);
        $mform->set_data($formdata);
        $mform->display();
    };
    echo nl2br("\n");
    // Navigation
    echo html_writer::link(new moodle_url('../ausleihverwaltung/saveDefect.php', array('id'=>$cm->id, 'resourceid'=>$fm_resourceid)), 'Zurück: Schaden vermerken', array('class'=>'btn btn-secondary'));
    echo html_writer::link(new moodle_url('/course/view.php', array('id'=>2)), 'Weiter: Home', array('class'=>'btn btn-secondary'));
};

/*********************** END CODE FOR SCHADENSDOKUMENTATION ***********************/

// Finish the page
echo $OUTPUT->footer();
