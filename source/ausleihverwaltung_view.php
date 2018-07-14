<?php
/*LOGIN*/
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

/*PAGE setzen*/
$PAGE->set_url('/mod/ausleihverwaltung/ausleihverwaltung_view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($ausleihverwaltung->name));
$PAGE->set_heading(format_string($course->fullname));


/*
// Um Tabelle >>ausleihverwaltung_borroweddevice<< zu belegen
$record1->duedate = 1530897520;
$record1->inventorynumber = '09876';
$record1->studentmatrikelnummer = 3156763;
$record1->studentmailaddress = 's153640@student.dhbw-mannheim.de';

$DB->insert_record('ausleihverwaltung_borroweddevice', $record1, $returnid=false, $bulk=false);

$record1->duedate = 1530859235;
$record1->inventorynumber = '73463574';
$record1->studentmatrikelnummer = 8528640;
$record1->studentmailaddress = 's145634@student.dhbw-mannheim.de';

$DB->insert_record('ausleihverwaltung_borroweddevice', $record1, $returnid=false, $bulk=false);

$record1->duedate = 1532500835;
$record1->inventorynumber = '134255';
$record1->studentmatrikelnummer = 1630452;
$record1->studentmailaddress = 's153660@student.dhbw-mannheim.de';

$DB->insert_record('ausleihverwaltung_borroweddevice', $record1, $returnid=false, $bulk=false);

$record1->duedate = 1524638435;
$record1->inventorynumber = '56765';
$record1->studentmatrikelnummer = 5654193;
$record1->studentmailaddress = 's153670@student.dhbw-mannheim.de';

$DB->insert_record('ausleihverwaltung_borroweddevice', $record1, $returnid=false, $bulk=false);

// Datensatz mit übergebener ID löschen
//$DB->delete_records_select('ausleihverwaltung_borroweddevice',"id = 2", $params=null);
*/


// Hier beginnt die Ausgabe
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($ausleihverwaltung->intro) {
    echo $OUTPUT->box(format_module_intro('ausleihverwaltung', $ausleihverwaltung, $cm->id), 'generalbox mod_introbox', 'ausleihverwaltungintro');
}

$strName = "Ausleihen-Übersicht";
echo $OUTPUT->heading($strName);

// Alle Datensätze aus der DB-Tabelle >>$ausleihverwaltung_borroweddevice<< abfragen.
$borrowed = $DB->get_records('ausleihverwaltung_borroweddevice');

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

// Alle Datensätze aus der DB-Tabelle >>ausleihverwaltung_responsible<< abfragen.
$responsibleDudes = $DB->get_records('ausleihverwaltung_responsible');

$table = new html_table();
$table->head = array('Name des Verantwortlichen', 'E-Mail des Verantwortlichen', 'Löschen');

//Für jeden Datensatz
foreach ($responsibleDudes as $responsible) {
    $name = $responsible->dudesname;
    $mail = $responsible->dudesmail;
    //Link zum löschen des Verantwortlichen in foreach-Schleife setzen
    $htmlLinkDelete = html_writer::link(new moodle_url('../ausleihverwaltung/ausleihverwaltung_delete.php', array('id' => $cm->id, 'responsibleid' => $responsible->id)), 'Delete', $attributes=null);
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

// Um Tabelle >>ausleihverwaltung_responsible<< zu belegen
    $record1 = new \stdClass();
    $record1->dudesname = $dudesName;
    $record1->dudesmail = $dudesMail;
    $DB->insert_record('ausleihverwaltung_responsible', $record1, $returnid=false, $bulk=false);

    //reload page so all Table views will be updated and Forms will be redisplayed
    redirect(new moodle_url('../ausleihverwaltung/ausleihverwaltung_view.php', array('id' => $cm->id)));

// $mform->render();

    // In this case you process validated data. $mform->get_data() returns data posted in form.
    // Creating instance of relevant API modules
    create_api_instances();
    $process_definition_id = ausleihverwaltung_get_process_definition_id("testttest");
    error_log("PROCESS DEFINITION ID IS: " . $process_definition_id);
    //$process_instance_id = ausleihverwaltung_start_process($process_definition_id, "test_key");
    //error_log("PROCESS INSTANCE ID IS: " . $process_instance_id);
    sleep(2);
    error_log("WAKEY WAKEY, BOYS AND GIRLS");
    //$taskid = ausleihverwaltung_check_for_input_required($process_instance_id);
    /*error_log("TASK ID IS: " . $taskid);
    if ($taskid != null) {
        error_log("EXECUTION OF TASK RESPONSE");
        $value1 = $fromform->email;
        $value2 = $fromform->name;
        $borrowedult = ausleihverwaltung_answer_input_required($task_id, $process_definition_id, $value1, $value2);
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

//FINISH
echo $OUTPUT->footer();
?>
