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
 * Prints a particular instance of checkdeadline
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_checkdeadline
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... checkdeadline instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('checkdeadline', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $checkdeadline  = $DB->get_record('checkdeadline', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $checkdeadline  = $DB->get_record('checkdeadline', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $checkdeadline->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('checkdeadline', $checkdeadline->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_checkdeadline\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $checkdeadline);
$event->trigger();


/*
// Um Tabelle >>borroweddevices<< zu belegen
$record1->duedate = 1530897520;
$record1->inventorynumber = '09876';
$record1->studentmatrikelnummer = 3156763;
$record1->studentmailaddress = 's153640@student.dhbw-mannheim.de';

$DB->insert_record('borroweddevices', $record1, $returnid=false, $bulk=false);

$record1->duedate = 1530859235;
$record1->inventorynumber = '73463574';
$record1->studentmatrikelnummer = 8528640;
$record1->studentmailaddress = 's145634@student.dhbw-mannheim.de';

$DB->insert_record('borroweddevices', $record1, $returnid=false, $bulk=false);

$record1->duedate = 1532500835;
$record1->inventorynumber = '134255';
$record1->studentmatrikelnummer = 1630452;
$record1->studentmailaddress = 's153660@student.dhbw-mannheim.de';

$DB->insert_record('borroweddevices', $record1, $returnid=false, $bulk=false);

$record1->duedate = 1524638435;
$record1->inventorynumber = '56765';
$record1->studentmatrikelnummer = 5654193;
$record1->studentmailaddress = 's153670@student.dhbw-mannheim.de';

$DB->insert_record('borroweddevices', $record1, $returnid=false, $bulk=false);

// Datensatz mit übergebener ID löschen
//$DB->delete_records_select('borroweddevices',"id = 2", $params=null);


// Um Tabelle >>responsibledudes<< zu belegen
$record1->dudesname = 'Mister Meister';
$record1->dudesmail = 'meistermeister@dude.com';

$DB->insert_record('responsibledudes', $record1, $returnid=false, $bulk=false);

$record1->dudesname = 'Mister Martin';
$record1->dudesmail = 'clemens.martin@dude.com';

$DB->insert_record('responsibledudes', $record1, $returnid=false, $bulk=false);

$record1->dudesname = 'Miss Frohwein';
$record1->dudesmail = 'miss.frohwein@dude.com';

$DB->insert_record('responsibledudes', $record1, $returnid=false, $bulk=false);
*/


// Print the page header.

$PAGE->set_url('/mod/checkdeadline/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($checkdeadline->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('checkdeadline-'.$somevar);
 */

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($checkdeadline->intro) {
    echo $OUTPUT->box(format_module_intro('checkdeadline', $checkdeadline, $cm->id), 'generalbox mod_introbox', 'checkdeadlineintro');
}

$strName = "Ausleihen-Übersicht";
echo $OUTPUT->heading($strName);

// Alle Datensätze aus der DB-Tabelle >>$borroweddevices<< abfragen.
$borrowed = $DB->get_records('borroweddevices');

$table = new html_table();
$table->head = array('ID','Due Date', 'Inventarnummer', 'Matrikelnummer', 'Studenten E-Mail');

//Für jeden Datensatz
foreach ($borrowed as $borrowed) {
    $rowId = $borrowed->id;
    $duedate = $borrowed->duedate;
    $inventorynumber = $borrowed->inventorynumber;
    $studentmatrikelnummer = $borrowed->studentmatrikelnummer;
    $studentmailaddress = $borrowed->studentmailaddress;
//Daten zuweisen an HTML-Tabelle
    $table->data[] = array($rowId, $duedate, $inventorynumber, $studentmatrikelnummer, $studentmailaddress);
}
//Tabelle ausgeben
echo html_writer::table($table);


$strName = "Verantwortlichen-Übersicht";
echo $OUTPUT->heading($strName);

// Alle Datensätze aus der DB-Tabelle >>responsibledudes<< abfragen.
$responsibleDudes = $DB->get_records('responsibledudes');

$table = new html_table();
$table->head = array('Name des Verantwortlichen', 'E-Mail des Verantwortlichen', 'Löschen');

//Für jeden Datensatz
foreach ($responsibleDudes as $responsible) {
    $name = $responsible->dudesname;
    $mail = $responsible->dudesmail;
    //Link zum löschen des Verantwortlichen in foreach-Schleife setzen
    $htmlLinkDelete = html_writer::link(new moodle_url('../checkdeadline/checkdeadline_delete.php', array('id' => $cm->id, 'responsibleid' => $responsible->id)), 'Delete', $attributes=null);
//Daten zuweisen an HTML-Tabelle
    $table->data[] = array($name, $mail, $htmlLinkDelete);
}
//Tabelle ausgeben
echo html_writer::table($table);


$strName = "Gibt es weitere Verantwortliche?";
echo $OUTPUT->heading($strName);

// Implement form for user
require_once(dirname(__FILE__).'/forms/checkDeadlineControllForm.php');
//require_once(dirname(__FILE__).'/forms/leihscheinForm.php');

$mform = new checkDeadlineControllForm_form();
//$mform = new leihschein_form();
// $mform->render();

// Form processing and displaying is done here
if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present on form
    echo "form is cancelled";
} else if ($fromform = $mform->get_data()) {

    $dudesName = $fromform->responsibleName;
    $dudesMail = $fromform->responsibleMail;

// Um Tabelle >>responsibledudes<< zu belegen
    $record1 = new \stdClass();
    $record1->dudesname = $dudesName;
    $record1->dudesmail = $dudesMail;
    $DB->insert_record('responsibledudes', $record1, $returnid=false, $bulk=false);

    //reload page so all Table views will be updated and Forms will be redisplayed
    redirect(new moodle_url('../checkdeadline/view.php', array('id' => $cm->id)));

// $mform->render();

    // In this case you process validated data. $mform->get_data() returns data posted in form.
    // Creating instance of relevant API modules
    create_api_instances();
    $process_definition_id = checkdeadline_get_process_definition_id("testttest");
    error_log("PROCESS DEFINITION ID IS: " . $process_definition_id);
    //$process_instance_id = checkdeadline_start_process($process_definition_id, "test_key");
    //error_log("PROCESS INSTANCE ID IS: " . $process_instance_id);
    sleep(2);
    error_log("WAKEY WAKEY, BOYS AND GIRLS");
    //$taskid = checkdeadline_check_for_input_required($process_instance_id);
    /*error_log("TASK ID IS: " . $taskid);
    if ($taskid != null) {
        error_log("EXECUTION OF TASK RESPONSE");
        $value1 = $fromform->email;
        $value2 = $fromform->name;
        $borrowedult = checkdeadline_answer_input_required($task_id, $process_definition_id, $value1, $value2);
        error_log("INPUT SEND RESULT IS: " . $borrowedult);
    }*/
} else {
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.

    // Set default data (if any)
    // Required for module not to crash as a course id is always needed
    $formdata = array('id' => $id);
    $mform->set_data($formdata);
    // displays the form
    $mform->display();

    error_log("TEST FROM AFTER DISPLAY");
}

// Finish the page.
echo $OUTPUT->footer();
