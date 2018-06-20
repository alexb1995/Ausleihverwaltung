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
 * Prints a particular instance of apeinsvier
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_apeinsvier
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace apeinsvier with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... apeinsvier instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('apeinsvier', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $apeinsvier  = $DB->get_record('apeinsvier', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $apeinsvier  = $DB->get_record('apeinsvier', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $apeinsvier->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('apeinsvier', $apeinsvier->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_apeinsvier\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $apeinsvier);
$event->trigger();


// DB INSERT -> Tabellen anlegen
/*
$record = new stdClass();
$record->name         = 'handy';
$record->description = 'dasd';
$record->serialnumber        = 'serial12';
$record->inventorynumber = 'invent123';
$record->comment        = 'Comment this Comment thisComment thisComment thisComment thisComment thisComment thisComment thisComment thisComment thisComment thisComment thisComment thisComment thisComment this';
$record->status = 0;
$record->amount         = 2;
$record->type = 0;
$record->maincategory    = "Handy";
$record->subcategory = "sub";

$DB->insert_record('resources', $record, $returnid=false, $bulk=false);


$record1->name         = 'iPhone';
$record1->description = 'beschde';
$record1->serialnumber        = 'serial14';
$record1->inventorynumber = 'invent567';
$record1->comment        = 'Comment that';
$record1->status = 3;
$record1->amount         = 4;
$record1->type = 1;
$record1->maincategory    = "Apple";
$record1->subcategory = "phone";

$DB->insert_record('resources', $record1, $returnid=false, $bulk=false)
*/

$resource = $DB->get_record_sql('SELECT name FROM {resources} WHERE id = ?', array(1));
//$resource = (string)$resource;

// Print the page header.

$PAGE->set_url('/mod/apeinsvier/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($apeinsvier->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('apeinsvier-'.$somevar);
 */

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($apeinsvier->intro) {
    echo $OUTPUT->box(format_module_intro('apeinsvier', $apeinsvier, $cm->id), 'generalbox mod_introbox', 'apeinsvierintro');
}

// Replace the following lines with you own code.
$strName = $resource->name;
echo $OUTPUT->heading($strName);
//$renderable = new \tool_demo\output\index_page('Some text');
//echo $output->render($renderable);

// Implement form for user
require_once(dirname(__FILE__).'/forms/simpleform.php');

$mform = new simplehtml_form();
// $mform->render();

error_log("TEST FROM BEFORE DISPLAY");

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
    error_log("TEST FROM DIRECTLY AFTER SUBMIT");
    $value1 = $fromform->email;
    $value2 = $fromform->name;

    echo $value1;
    error_log($value1);

  //In this case you process validated data. $mform->get_data() returns data posted in form.
  //Creating instance of relevant API modules
  create_api_instances();
  $process_definition_id = apeinsvier_get_process_definition_id("myProcess");
  error_log("PROCESS DEFINITION ID IS: " . $process_definition_id);
  $process_instance_id = apeinsvier_start_process($process_definition_id, "test_key");
  error_log("PROCESS INSTANCE ID IS: " . $process_instance_id);
  sleep(2);
  error_log("WAKEY WAKEY, BOYS AND GIRLS");
  $taskid = apeinsvier_check_for_input_required($process_instance_id);
  error_log("TASK ID IS: " . $taskid);
  if ($taskid != null) {
    error_log("EXECUTION OF TASK RESPONSE");
    $value1 = $fromform->email;
    $value2 = $fromform->name;
    $result = apeinsvier_answer_input_required($task_id, $process_definition_id, $value1, $value2);
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

$test = "gib mal was aus";
$attributes = array();

p($test, $strip=false);
echo html_writer::link(new moodle_url('/grade/report/user/index.php', array('id' => $course->id)), $test, $attributes=null);
echo($test."\n");

$resource = $DB->get_records('resources');
$table = new html_table();
$table->head = array('ID','Name', 'Description', 'Serialnumber', 'Inventorynumber', 'Comment', 'Status', 'Amount', 'Type', 'Maincategory', 'Subcategory', 'Edit', 'Delete');

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
$htmlLink = html_writer::link(new moodle_url('../apeinsvier/edit.php', array('id' => $course->id, 'resourceid' => $res->id)), 'Edit', $attributes=null);
$htmlLinkDelete = html_writer::link(new moodle_url('/grade/report/user/index.php', array('id' => $course->id)), 'Delete', $attributes=null);

$table->data[] = array($id, $name, $description, $serialnumber, $inventorynumber, $comment, $status, $amount, $type, $maincategory, $subcategory, $htmlLink, $htmlLinkDelete);
}
echo html_writer::table($table);

// Finish the page.
echo $OUTPUT->footer();
