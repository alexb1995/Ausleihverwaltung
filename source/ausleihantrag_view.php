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
 * Prints a particular instance of ausleihantrag
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_ausleihverwaltung
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace ausleihantrag with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... ausleihantrag instance ID - it should be named as the first character of the module.

if ($id) {
    $cm           = get_coursemodule_from_id('ausleihverwaltung', $id, 0, false, MUST_EXIST);
    $course       = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $ausleihverwaltung  = $DB->get_record('ausleihverwaltung', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $ausleihverwaltung  = $DB->get_record('ausleihverwaltung', array('id' => $n), '*', MUST_EXIST);
    $course       = $DB->get_record('course', array('id' => $ausleihverwaltung->course), '*', MUST_EXIST);
    $cm           = get_coursemodule_from_instance('ausleihverwaltung', $ausleihverwaltung->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
};

require_login($course, true, $cm);

$event = \mod_ausleihverwaltung\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $ausleihverwaltung);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/ausleihverwaltung/ausleihantrag_view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($ausleihverwaltung->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('ausleihantrag-'.$somevar);
 */

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($ausleihverwaltung->intro) {
    echo $OUTPUT->box(format_module_intro('ausleihantrag', $ausleihverwaltung, $cm->id), 'generalbox mod_introbox', 'ausleihantragintro');
}



// Replace the following lines with you own code.
echo $OUTPUT->heading('Ausleihantrag stellen:');


// Implement form for user
require_once(dirname(__FILE__).'/forms/simpleformNeu.php');

$mform = new simplehtml_form();
// $mform->render();

// error_log("TEST FROM BEFORE DISPLAY");

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
    $value1 = $fromform->ausleiher;
    $value2 = $fromform->matrikel;
    $value3 = $fromform->mail;
    $value4 = $fromform->grund;
    $value5 = $fromform->anmerkung;
    $value6 = $fromform->returnDate;
    $value7 = $fromform->deviceId;


    // Um Tabelle >>ausleihverwaltung_borrowed<< zu belegen
    $record1 = new \stdClass();

    $record1->studentname = $value1;
    $record1->studentmatrikelnummer = $value2;
    $record1->studentmailaddress = $value3;
    $record1->comment = $value4;
    $record1->borrowreason = $value5;
    $record1->duedate = $value6;
    $record1->resourceid = $value7;

    $tomorrow = new DateTime("1 day", core_date::get_server_timezone_object());
    $tomorrowint = $tomorrow->getTimestamp();

    $record1->borrowdate = $tomorrowint;
            
    $record1->accepted = true;

    $recordid = $DB->insert_record('ausleihverwaltung_borrowed', $record1, $returnid=true, $bulk=false);

    echo "Antrag wurde verschickt!";

} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
  // or on the first display of the form.

  // Set default data (if any)
  // Required for module not to crash as a course id is always needed
  $formdata = array('id' => $id);
  $mform->set_data($formdata);
  //displays the form
  $mform->display();

}

$strName = "Ressourcen-Übersicht";
echo $OUTPUT->heading($strName);

$attributes = array();
// Alle Datensätze aus der DB-Tabelle >>resources<< abfragen.
$resource = $DB->get_records('ausleihverwaltung_resources');

$table = new html_table();
$table->head = array('ID','Name', 'Beschreibung', 'Seriennummer', 'Inventarnummer', 'Kommentar', 'Status', 'Menge', 'Typ', 'Hauptkategorie', 'Subkategorie', 'Schaden', 'Bearbeiten', 'Löschen');

//Für jeden Datensatz
foreach ($resource as $res) {
    $id = $res->id;
    $name = $res->name;
    $description = $res->description;
    $serialnumber = $res->serialnumber;
    $inventorynumber = $res->inventorynumber;
    $comment = $res->comment;
    $status = $res->status;
    $amount = $res->amount;
    $type = $res->type;
    $maincategory = $res->maincategory;
    $subcategory = $res->subcategory;
    $defect = $res->defect;
//Link zum Bearbeiten der aktuellen Ressource in foreach-Schleife setzen
    $htmlLink = html_writer::link(new moodle_url('../ausleihverwaltung/resources_edit.php', array('id' => $cm->id, 'resourceid' => $res->id)), 'Edit', $attributes=null);
//Analog: Link zum Löschen...
    $htmlLinkDelete = html_writer::link(new moodle_url('../ausleihverwaltung/resources_delete.php', array('id' => $cm->id, 'resourceid' => $res->id)), 'Delete', $attributes=null);
//Daten zuweisen an HTML-Tabelle
    $table->data[] = array($id, $name, $description, $serialnumber, $inventorynumber, $comment, $status, $amount, $type, $maincategory, $subcategory, $defect, $htmlLink, $htmlLinkDelete);
}
//Tabelle ausgeben
echo html_writer::table($table);

echo $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/view.php', array('id' => $cm->id)), 'Home');

// Finish the page.
echo $OUTPUT->footer();