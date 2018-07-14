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
$responsibleID = $_GET['responsibleID'];
$dudesName = $_GET['responsibleName'];

/*PAGE Setzen*/
$PAGE->set_url('/mod/ausleihverwaltung/ausleihverwaltung_deleteaccept.php', array('id' => $cm->id,'responsibleID' => $responsibleID));
$PAGE->set_title(format_string($ausleihverwaltung->name));
echo nl2br("\n");
$PAGE->set_heading(format_string($course->fullname));

// Hier beginnt die Ausgabe
echo $OUTPUT->header();
echo nl2br("\n");
$strName = "Löschen erfolgreich";
echo $OUTPUT->heading($strName);
echo nl2br("\n");

$ausleihverwaltung_responsible = 'ausleihverwaltung_responsible';
// Datensatz mit übergebener ID löschen
$DB->delete_records_select($ausleihverwaltung_responsible,"id ='".$responsibleID."'", $params=null);

//Erfolgsmeldung
$message = "Verantwortlicher mit dem Namen " .$dudesName. " ist gelöscht.";

echo $message;
echo nl2br("\n");
echo nl2br("\n");
echo nl2br("\n");

//Funktionstaste zum Fortfahren definieren
echo $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/ausleihverwaltung_view.php', array('id' => $cm->id)), 'OK');

//Finish
echo $OUTPUT->footer();
?>
