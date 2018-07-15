<?php
/*LOGIN*/
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

/*PAGE setzen*/
$PAGE->set_url('/mod/checkdeadline/checkdeadline_view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($checkdeadline->name));
$PAGE->set_heading(format_string($course->fullname));



// Um Tabelle >>checkdeadline_borroweddevice<< zu belegen
$record1->duedate = 1531652072;
$record1->resourceid = 1;
$record1->studentmatrikelnummer = 3156763;
$record1->studentmailaddress = 's151634@student.dhbw-mannheim.de';
$record1->borrowdate = 1515653672;
$record1->studentname = 'Hans Peter';
$record1->borrowreason = 'Einfach bock';
$record1->comment = 'Für die krasseste Vorlesung auf diesem Planeten bruh.';
$record1->accepted = true;
$record1->returned = false;

$DB->insert_record('checkdeadline_borroweddevice', $record1, $returnid=false, $bulk=false);
/*
$record1->duedate = 1530859235;
$record1->resourceid = 2;
$record1->studentmatrikelnummer = 8528640;
$record1->studentmailaddress = 's145634@student.dhbw-mannheim.de';
$record1->borrowdate = 1518332072;
$record1->studentname = 'Hans Wurst';
$record1->borrowreason = 'Bli Bla Blubb';
$record1->comment = 'Ich bin ein Dummkopf';
$record1->accepted = false;
$record1->returned = false;

$DB->insert_record('checkdeadline_borroweddevice', $record1, $returnid=false, $bulk=false);

$record1->duedate = 1532500835;
$record1->resourceid = 3;
$record1->studentmatrikelnummer = 1630452;
$record1->studentmailaddress = 's153660@student.dhbw-mannheim.de';
$record1->borrowdate = 1520751272;
$record1->studentname = 'Hans Mustermann';
$record1->borrowreason = 'Ich will einfach haben';
$record1->comment = 'wojfweofjneofginet2o';
$record1->accepted = true;
$record1->returned = true;

$DB->insert_record('checkdeadline_borroweddevice', $record1, $returnid=false, $bulk=false);

$record1->duedate = 1524638435;
$record1->resourceid = 4;
$record1->studentmatrikelnummer = 5654193;
$record1->studentmailaddress = 's153670@student.dhbw-mannheim.de';
$record1->borrowdate = 1523429672;
$record1->studentname = 'Hans Musterfrau';
$record1->borrowreason = 'für eBay zum Verkaufen safe';
$record1->comment = 'öerkfjwetgiojt+#pfidhqäofig';
$record1->accepted = false;
$record1->returned = false;

$DB->insert_record('checkdeadline_borroweddevice', $record1, $returnid=false, $bulk=false);

// Datensatz mit übergebener ID löschen
//$DB->delete_records_select('checkdeadline_borroweddevice',"id = 2", $params=null);
*/


// Hier beginnt die Ausgabe
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($checkdeadline->intro) {
    echo $OUTPUT->box(format_module_intro('checkdeadline', $checkdeadline, $cm->id), 'generalbox mod_introbox', 'checkdeadlineintro');
}

$strName = "Ausleihen-Übersicht";
echo $OUTPUT->heading($strName);

// Alle Datensätze aus der DB-Tabelle >>$checkdeadline_borroweddevice<< abfragen.
$borrowed = $DB->get_records('checkdeadline_borroweddevice');

$table = new html_table();
$table->head = array('Geräte ID', 'Gerätename', 'Ausgeliehen am', 'Fällig bis', 'Matrikelnummer', 'Studenten Name', 'E-Mail', 'Ausleihgrund', 'Rückgabe');

//Für jeden Datensatz
foreach ($borrowed as $borrowed) {
    if ($borrowed->accepted && !($borrowed->returned)){
        //Get Name of the Resource that was borrowed
        $resourceId = $borrowed->resourceid;
        $resourceName = $DB->get_field('apeinsvier_resources', 'name', array('id'=> $resourceId));

        //Transform Epoch time to human Time for <<borroweddate>>
        $borrowdateepoch = $borrowed->borrowdate;
        $borrowdate = new DateTime("@$borrowdateepoch");
        $borrowdate = $borrowdate->format('d-m-Y');

        //Transform Epoch time to human Time for <<duedate>>
        $duedateepoch = $borrowed->duedate;
        if ($duedateepoch == 9999999999){
            $duedate = 'kein Rückgabedatum vereinbart';
        }else{
            $duedate = new DateTime("@$duedateepoch");
            $duedate = $duedate->format('d-m-Y');

        }

        $studentmatrikelnummer = $borrowed->studentmatrikelnummer;
        $studentname = $borrowed->studentname;
        $studentmailaddress = $borrowed->studentmailaddress;
        $borrowreason = $borrowed->borrowreason;
        $comment = $borrowed->comment;

        $returnButton = $OUTPUT->single_button(new moodle_url('../checkdeadline/saveDefect.php', array('id' => $cm->id, 'resourceid' => $resourceId)), 'Rückgabe der Resource', $attributes=null);

//Daten zuweisen an HTML-Tabelle
        $table->data[] = array($resourceId, $resourceName, $borrowdate, $duedate, $studentmatrikelnummer, $studentname, $studentmailaddress, $borrowreason, $returnButton);
    }
}
//Tabelle ausgeben
echo html_writer::table($table);


$strName = "Verantwortlichen-Übersicht";
echo $OUTPUT->heading($strName);

// Alle Datensätze aus der DB-Tabelle >>checkdeadline_responsible<< abfragen.
$responsibleDudes = $DB->get_records('checkdeadline_responsible');

$table = new html_table();
$table->head = array('Name des Verantwortlichen', 'E-Mail des Verantwortlichen', 'Löschen');

//Für jeden Datensatz
foreach ($responsibleDudes as $responsible) {
    $name = $responsible->dudesname;
    $mail = $responsible->dudesmail;
    //Link zum löschen des Verantwortlichen in foreach-Schleife setzen
    $deleteButton = $OUTPUT->single_button(new moodle_url('../checkdeadline/checkdeadline_delete.php', array('id' => $cm->id, 'responsibleid' => $responsible->id)), 'Delete', $attributes=null);
//Daten zuweisen an HTML-Tabelle
    $table->data[] = array($name, $mail, $deleteButton);
}
//Tabelle ausgeben
echo html_writer::table($table);


$strName = "Gibt es weitere Verantwortliche?";
echo $OUTPUT->heading($strName);

// Implement form for user
require_once(dirname(__FILE__) . '/forms/newresponsibleform.php');
//require_once(dirname(__FILE__).'/forms/leihscheinForm.php');

$mform = new newresponsibleform_form();
//$mform = new leihschein_form();
// $mform->render();

// Form processing and displaying is done here
if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present on form
    echo "form is cancelled";
} else if ($fromform = $mform->get_data()) {

    $dudesName = $fromform->responsibleName;
    $dudesMail = $fromform->responsibleMail;

// Um Tabelle >>checkdeadline_responsible<< zu belegen
    $record1 = new \stdClass();
    $record1->dudesname = $dudesName;
    $record1->dudesmail = $dudesMail;
    $DB->insert_record('checkdeadline_responsible', $record1, $returnid=false, $bulk=false);

    //reload page so all Table views will be updated and Forms will be redisplayed
    redirect(new moodle_url('../checkdeadline/checkdeadline_view.php', array('id' => $cm->id)));

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

//FINISH
echo $OUTPUT->footer();
?>
