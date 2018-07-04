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

$PAGE->set_url('/mod/apsechseins/view.php', array('id' => $cm->id));
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
require_once(dirname(__FILE__).'/forms/viewform.php');
require_once(dirname(__FILE__).'/forms/newform.php');

// CODE FOR SCHADENSDOKUMENTATION

// $resourceid for testing
$resourceid = 2;
$resource = $DB->get_record('schaeden', array('resourceid'=>$resourceid));
$schaden = $resource->schaden;
if(empty($schaden)) {
	echo 'Bitte legen Sie einen Schadensvermerk für die ausgewählte Ressource an:';
	$mform = new newhtml_form(null, array('resourceid'=>$resourceid, 'schaden'=>$schaden));
	$mform->display();
} else {
	// Use a table for displaying the defect of the currently selected resource and the option to edit the record
	// Information that a record for this resource already exists
	echo 'Es existiert bereits ein Schadensvermerk für die ausgewählte Ressource:';
	$mform = new viewhtml_form(null, array('resourceid'=>$resourceid, 'schaden'=>$schaden));
	$mform->display();
};



// TESTING ONLY
// Create table
$resources = $DB->get_records('schaeden');
$table = new html_table();
$table->head = array('Ressourcen-ID', 'Schaden');
foreach ($resources as $resource) {
	$resourceid = $resource->resourceid;
	$schaden = $resource->schaden;
	$htmlEdit = html_writer::link(new moodle_url('../apsechseins/edit.php', array('id' => $cm->id, 'resourceid' => $resourceid)), 'Eintrag bearbeiten', $attributes=null);
	$htmlDelete = html_writer::link(new moodle_url('../apsechseins/delete.php', array('id' => $cm->id, 'resourceid' => $resourceid)), 'Eintrag löschen', $attributes=null);
	$table->data[] = array($resourceid, $schaden, $htmlEdit, $htmlDelete);
};
echo html_writer::table($table);




// End code for Ausleihverwaltung - Schadensdokumentation


// Finish the page.
echo $OUTPUT->footer();
