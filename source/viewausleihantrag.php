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
 * @package    mod_ausleihantrag
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
    $cm         = get_coursemodule_from_id('ausleihantrag', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $ausleihantrag  = $DB->get_record('ausleihantrag', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $ausleihantrag  = $DB->get_record('ausleihantrag', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $ausleihantrag->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('ausleihantrag', $ausleihantrag->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_ausleihantrag\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $ausleihantrag);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/ausleihantrag/viewausleihantrag.php', array('id' => $cm->id));
$PAGE->set_title(format_string($ausleihantrag->name));
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
if ($ausleihantrag->intro) {
    echo $OUTPUT->box(format_module_intro('ausleihantrag', $ausleihantrag, $cm->id), 'generalbox mod_introbox', 'ausleihantragintro');
}



// Replace the following lines with you own code.
echo $OUTPUT->heading('Kategorie der Ressource auswählen.');


// Implement form for user
require_once(dirname(__FILE__).'/forms/simpleformNeu.php');

$mform = new simplehtml_form();
// $mform->render();

// error_log("TEST FROM BEFORE DISPLAY");

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
    $value1 = $fromform->email;
    $value2 = $fromform->name;

    echo $value1;
    error_log($value1);

  //In this case you process validated data. $mform->get_data() returns data posted in form.
  //Creating instance of relevant API modules
  create_api_instances();
  $process_definition_id = ausleihantrag_get_process_definition_id("testttest");
  error_log("PROCESS DEFINITION ID IS: " . $process_definition_id);
  $process_instance_id = ausleihantrag_start_process($process_definition_id, "test_key");
  error_log("PROCESS INSTANCE ID IS: " . $process_instance_id);
  sleep(2);
  error_log("WAKEY WAKEY, BOYS AND GIRLS");
  $taskid = ausleihantrag_check_for_input_required($process_instance_id);
  error_log("TASK ID IS: " . $taskid);
  if ($taskid != null) {
    error_log("EXECUTION OF TASK RESPONSE");
    $value1 = $fromform->email;
    $value2 = $fromform->name;
    $result = ausleihantrag_answer_input_required($taskid, $process_definition_id, $value1, $value2);
    error_log("INPUT SEND RESULT IS: " . $result);
  }
} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
  // or on the first display of the form.

  // Set default data (if any)
  // Required for module not to crash as a course id is always needed
  $formdata = array('id' => $id);
  $mform->set_data($formdata);
  //displays the form
  $mform->display();

  error_log("TEST FROM AFTER DISPLAY");
}
// Alle Datensätze aus der DB-Tabelle >>resources<< abfragen.
$resource = $DB->get_records('resources');
$table = new html_table();
$table->head = array('ID','Name', 'Description', 'Serialnumber', 'Inventorynumber', 'Comment', 'Status', 'Amount', 'Type', 'Maincategory', 'Subcategory');
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
//Link zum Bearbeiten der aktuellen Ressource in foreach-Schleife setzen

//Analog: Link zum Löschen...

//Daten zuweisen an HTML-Tabelle
$table->data[] = array($id, $name, $description, $serialnumber, $inventorynumber, $comment, $status, $amount, $type, $maincategory, $subcategory);
}
//Tabelle ausgeben
echo html_writer::table($table);

// Alle Datensätze aus der DB-Tabelle >>resources<< abfragen.
$resource = $DB->get_records('apeinsvier_resources');
$table = new html_table();
$table->head = array('ID','Name', 'Description', 'Serialnumber', 'Inventorynumber', 'Comment', 'Status', 'Amount', 'Type', 'Maincategory', 'Subcategory');
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
//Link zum Bearbeiten der aktuellen Ressource in foreach-Schleife setzen
$htmlLink = html_writer::link(new moodle_url('../apeinsvier/edit.php', array('id' => $cm->id, 'resourceid' => $res->id)));
//Analog: Link zum Löschen...
$htmlLinkDelete = html_writer::link(new moodle_url('../apeinsvier/delete.php', array('id' => $cm->id, 'resourceid' => $res->id)));
//Daten zuweisen an HTML-Tabelle
$table->data[] = array($id, $name, $description, $serialnumber, $inventorynumber, $comment, $status, $amount, $type, $maincategory, $subcategory, $htmlLink, $htmlLinkDelete);
}
//Tabelle ausgeben
echo html_writer::table($table);

// Finish the page.
echo $OUTPUT->footer();
