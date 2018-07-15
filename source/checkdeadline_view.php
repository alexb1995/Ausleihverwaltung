<?php
/*LOGIN*/
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... checkdeadline instance ID - it should be named as the first character of the module.

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
$PAGE->set_url('/mod/ausleihverwaltung/checkdeadline_view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($ausleihverwaltung->name));
$PAGE->set_heading(format_string($course->fullname));

// Datensatz mit übergebener ID löschen
//$DB->delete_records_select('ausleihverwaltung_borrowed',"id = 2", $params=null);


// Hier beginnt die Ausgabe
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($ausleihverwaltung->intro) {
    echo $OUTPUT->box(format_module_intro('checkdeadline', $ausleihverwaltung, $cm->id), 'generalbox mod_introbox', 'checkdeadlineintro');
}

$strName = "Ausleihen-Übersicht";
echo $OUTPUT->heading($strName);

// Alle Datensätze aus der DB-Tabelle >>$ausleihverwaltung_borrowed<< abfragen.
$borrowed = $DB->get_records('ausleihverwaltung_borrowed');

$table = new html_table();
$table->head = array('Geräte ID', 'Gerätename', 'Ausgeliehen am', 'Fällig bis', 'Matrikelnummer', 'Studenten Name', 'E-Mail', 'Ausleihgrund', 'Leihschein', 'Rückgabe');

//Für jeden Datensatz
foreach ($borrowed as $borrowed) {
    if ($borrowed->accepted && !($borrowed->returned)){
        //Get Name of the Resource that was borrowed
        $resourceId = $borrowed->resourceid;
        $resourceName = $DB->get_field('ausleihverwaltung_resources', 'name', array('id'=> $resourceId));

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

        $leihscheinButton = $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/generate_leihschein.php', array('id' => $cm->id, 'borrowedid' => $borrowed->id)), 'Leihschein', $attributes=null);
        $returnButton = $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/saveDefect.php', array('id' => $cm->id, 'resourceid' => $resourceId)), 'Rückgabe', $attributes=null);

//Daten zuweisen an HTML-Tabelle
        $table->data[] = array($resourceId, $resourceName, $borrowdate, $duedate, $studentmatrikelnummer, $studentname, $studentmailaddress, $borrowreason, $leihscheinButton, $returnButton);
    }
}
//Tabelle ausgeben
echo html_writer::table($table);

$strName = "Leihschein abgeben";
echo $OUTPUT->heading($strName);

require_once(dirname(__FILE__).'/forms/formablageschein.php');

$mform = new ablageleihschein_form();
// $mform->render();

// error_log("TEST FROM BEFORE DISPLAY");

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
    $value1 = $fromform->userfile;  //value1 = uploaded file     

    $content = $mform->get_file_content('userfile');  //To get the contents of the file
    $name = $mform->get_new_filename('userfile'); //To get the name of the chosen file
    $path = '/opt/bitnami/moodle/uploads/'.$name; //Combine /opt/bitnami/moodle/uploads/ and the name of the uploaded file to get the path  
    $success = $mform->save_file('userfile', $path, false);// To save the chosen file to the server filesystem (such as to moodledata folder)
    

     //echo $path;
    //echo $content;
    error_log($value1);

  

} else {
  $formdata = array('id' => $id);
  $mform->set_data($formdata);

  $mform->display();

}

$strName = "Verantwortlichen-Übersicht";
echo $OUTPUT->heading($strName);

// Alle Datensätze aus der DB-Tabelle >>ausleihverwaltung_resp<< abfragen.
$responsibleDudes = $DB->get_records('ausleihverwaltung_resp');

$table = new html_table();
$table->head = array('Name des Verantwortlichen', 'E-Mail des Verantwortlichen', 'Löschen');

//Für jeden Datensatz
foreach ($responsibleDudes as $responsible) {
    $name = $responsible->dudesname;
    $mail = $responsible->dudesmail;
    //Link zum löschen des Verantwortlichen in foreach-Schleife setzen
    $deleteButton = $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/checkdeadline_delete.php', array('id' => $cm->id, 'responsibleid' => $responsible->id)), 'Delete', $attributes=null);
//Daten zuweisen an HTML-Tabelle
    $table->data[] = array($name, $mail, $deleteButton);
}
//Tabelle ausgeben
echo html_writer::table($table);

$strName = "Gibt es weitere Verantwortliche?";
echo $OUTPUT->heading($strName);

// Implement form for user
require_once(dirname(__FILE__) . '/forms/newresponsibleform.php');

$mform = new newresponsibleform_form();

// Form processing and displaying is done here
if ($mform->is_cancelled()) {
    // Handle form cancel operation, if cancel button is present on form
    echo "form is cancelled";
} else if ($fromform = $mform->get_data()) {

    $dudesName = $fromform->responsibleName;
    $dudesMail = $fromform->responsibleMail;

// Um Tabelle >>ausleihverwaltung_resp<< zu belegen
    $record1 = new \stdClass();
    $record1->dudesname = $dudesName;
    $record1->dudesmail = $dudesMail;
    $DB->insert_record('ausleihverwaltung_resp', $record1, $returnid=false, $bulk=false);

    //reload page so all Table views will be updated and Forms will be redisplayed
    redirect(new moodle_url('../ausleihverwaltung/checkdeadline_view.php', array('id' => $cm->id)));

    // In this case you process validated data. $mform->get_data() returns data posted in form.
    // Creating instance of relevant API modules
    create_api_instances();
    $process_definition_id = ausleihverwaltung_get_process_definition_id("testttest");
    error_log("PROCESS DEFINITION ID IS: " . $process_definition_id);
    //$process_instance_id = checkdeadline_start_process($process_definition_id, "test_key");
    //error_log("PROCESS INSTANCE ID IS: " . $process_instance_id);
    sleep(2);
    error_log("WAKEY WAKEY, BOYS AND GIRLS");
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