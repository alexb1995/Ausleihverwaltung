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
};

require_login($course, true, $cm);

$event = \mod_apsechseins\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $apsechseins);
$event->trigger();

// Print the page header.
$PAGE->set_url('/mod/apsechseins/view.php', array('id'=>$cm->id));
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

/************************* CODE FOR SCHADENSDOKUMENTATION *************************/

// First or second load of website?
if (isset($_GET['resourceid'])) {
	$resourceid = $_GET['resourceid'];
	$durchlauf = 1;
} else {
	$durchlauf = 2;
};

$url = $PAGE->url;
$strUrl = $url.'';
// Catch error: resourceid not available
switch ($durchlauf) {
	case 1:
		// If first load of the site
		$resource = $DB->get_record('schaeden', array('id'=>$resourceid));	
		$defect = $resource->defect;
		// If no defect notice exists for the selected resource
		if (empty($defect)) {
			echo 'Bitte legen Sie einen Schadensvermerk für die ausgewählte Ressource an:';
		// If a defect notice already exists for the selected resource
		} else {
			echo 'Es existiert ein Schadensvermerk für die ausgewählte Ressource. Sie können ihn nun bearbeiten:';
		};
		require_once(dirname(__FILE__).'/forms/editform.php');
		$editform = new edithtml_form(null,array('resourceid'=>$resourceid, 'defect'=>$defect));
		$editform->display();
		break;
	case 2:
		echo 'zweiter Durchlauf';
		/*/$resource = $DB->get_record('schaeden', array('id'=>$resourceid));
        if ($fromform = $mform->get_data()) {
	        $record = new stdClass();
	        $record->id 				= $fromform->resourceid;
	        $record->name 				= $resource->name;
	        $record->description  		= $resource->description;
	        $record->serialnumber 		= $resource->serialnumber;
	        $record->inventorynumber	= $resource->inventorynumber;
	        $record->comment 			= $resource->comment;
	        $record->status 			= $resource->status;
	        $record->amount 			= $resource->amount;
	        $record->type 				= $resource->type;
	        $record->maincategory 		= $resource->maincategory;
	        $record->subcategory 		= $resource->subcategory;
	        $record->defect 			= $fromfort->defect;
	    };
	    $DB->update_record('schaeden', $record, $bulk = false); 
        echo "Die Ressource wurde mit der ID ".$resourceid." wurde erfolgreich aktualisiert.";
		break;*/
};

// TESTING ONLY
// Create table
$resources = $DB->get_records('schaeden');
$table = new html_table();
$table->head = array('Ressourcen-ID', 'Schaden');
foreach ($resources as $resource) {
	$resourceid = $resource->resourceid;
	$defect = $resource->defect;
	$htmlEdit = html_writer::link(new moodle_url('../apsechseins/edit.php', array('id'=>$cm->id,'resourceid' =>$resourceid)), 'Eintrag bearbeiten', $attributes=null);
	$htmlDelete = html_writer::link(new moodle_url('../apsechseins/delete.php', array('id'=>$cm->id, 'resourceid'=>$resourceid)), 'Eintrag löschen', $attributes=null);
	$table->data[] = array($resourceid, $defect, $htmlEdit, $htmlDelete);
};
echo html_writer::table($table);
// END TESTING ONLY


// End code for Ausleihverwaltung - Schadensdokumentation


// Finish the page.
echo $OUTPUT->footer();
