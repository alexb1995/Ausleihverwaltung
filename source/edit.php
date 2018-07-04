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
 * Prints a particular instance of apsechseins
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_apsechseins
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... apsechseins instance ID - it should be named as the first character of the module.

if ($id) {
    $cm           = get_coursemodule_from_id('apsechseins', $id, 0, false, MUST_EXIST);
    $course       = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $apsechseins  = $DB->get_record('apsechseins', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $apsechseins  = $DB->get_record('apsechseins', array('id' => $n), '*', MUST_EXIST);
    $course       = $DB->get_record('course', array('id' => $apsechseins->course), '*', MUST_EXIST);
    $cm           = get_coursemodule_from_instance('apsechseins', $apsechseins->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_apsechseins\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $apsechseins);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/apsechseins/edit.php', array('id' => $cm->id, 'resourceid' => $_GET['resourceid']));
$PAGE->set_title(format_string($apsechseins->name));
$PAGE->set_heading(format_string($course->fullname));

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($apsechseins->intro) {
    echo $OUTPUT->box(format_module_intro('apsechseins', $apsechseins, $cm->id), 'generalbox mod_introbox', 'apsechseinsintro');
}

// Replace the following lines with you own code.
echo $OUTPUT->heading('Schadensdokumentation');

// Implement form for user
require_once(dirname(__FILE__).'/forms/simpleform.php');
$mform = new simplehtml_form();


// Form processing and displaying is done here
if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present on form
  echo 'Aktion abgebrochen';
} else if ($fromform = $mform->get_data()) {
  // In this case you process validated data. $mform->get_data() returns data posted in form.
  // Creating instance of relevant API modules
  create_api_instances();
  $process_definition_id = apsechseins_get_process_definition_id("testttest");
  error_log("PROCESS DEFINITION ID IS: " . $process_definition_id);
  $process_instance_id = apsechseins_start_process($process_definition_id, "test_key");
  error_log("PROCESS INSTANCE ID IS: " . $process_instance_id);
  sleep(2);
  error_log("WAKEY WAKEY, BOYS AND GIRLS");
  $taskid = apsechseins_check_for_input_required($process_instance_id);
  error_log("TASK ID IS: " . $taskid);
  if ($taskid != null) {
    error_log("EXECUTION OF TASK RESPONSE");
    $resourceid = $fromform->resourceid;
    $schaden = $fromform->schaden;
    $result = apsechseins_answer_input_required($taskid, $process_definition_id, $resourceid, $schaden);
    error_log("INPUT SEND RESULT IS: " . $result);
  }
} else {
	// $resourceid for testing
	$resourceid = 1;

	$resource = $DB->get_record('schaeden', array('resourceid'=>$resourceid));
	$schaden = $resource->schaden;
	if(empty($schaden)) {
		echo 'kein Schaden vorhanden';
	} else {
		// Use a table for displaying the defect of the currently selected resource and the option to edit the record
		// Create table
		echo 'Es wurde bereits ein Schaden für diese Ressource vermerkt:';
		$table = new html_table();
		$table->head = array('Ressourcen-ID', 'Schaden');
		$table->data[] = array($resourceid, $schaden);
		echo html_writer::table($table);
		$htmlLink = html_writer::link(new moodle_url('../apsechseins.edit.php', array('id'=>$cm->id, 'resourceid'=>$resourceid)), 'Edit' $attributes=null);

	};
};


/*{
  // This branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
  // or on the first display of the form.
 
  // Set default data (if any)
  // Required for module not to crash as a course id is always needed
  $formdata = array('id' => $id);
  $mform->set_data($formdata);
  // Displays the form
  $mform->display();

  error_log("TEST FROM AFTER DISPLAY");
}*/

// Code for Ausleihverwaltung - Schadensdokumentation
// ResourceID for testing
/* PLAN: 
    ResourceID in Input-Feld, standardmäßig deaktiviert
    Feld "Schäden" ausgefüllt, deaktiviert
    Über Button aktivierbar
*/
/*
// Get currently selected resource from DB
$resource = $DB->get_record('schaeden', array('resourceid'=>$resourceid));
// Get defect of currently selected resource
$schaden = $resource->schaden;
if(empty($schaden)) {
  echo "KEIN SCHADEN VORHANDEN";
} else {
  echo "SCHADEN VORHANDEN: " . $schaden;
};
// Create table for output
$table = new html_table();
$table->head = array('Resssourcen-ID', 'Schaden');
$table->data[] = array('1', $schaden);

echo html_writer::table($table);

// End code for Ausleihverwaltung - Schadensdokumentation

*/
// Finish the page.
echo $OUTPUT->footer();
