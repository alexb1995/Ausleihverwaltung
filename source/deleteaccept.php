<?php

/*LOGIN*/
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... apeinsdrei instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('apeinsdrei', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $apeinsdrei  = $DB->get_record('apeinsdrei', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $apeinsdrei  = $DB->get_record('apeinsdrei', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $apeinsdrei->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('apeinsdrei', $apeinsdrei->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_apeinsdrei\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $apeinsdrei);
$event->trigger();
$resID = $_GET['resourceid'];
$resName = $_GET['resname'];

/*PAGE Setzen*/
$PAGE->set_url('/mod/apeinsdrei/deleteaccept.php', array('id' => $cm->id,'resourceid' => $resID));
$PAGE->set_title(format_string($apeinsdrei->name));
echo nl2br("\n");
$PAGE->set_heading(format_string($course->fullname));

// Hier beginnt die Ausgabe
echo $OUTPUT->header();
echo nl2br("\n");
$strName = "Löschen erfolgreich";
echo $OUTPUT->heading($strName);
echo nl2br("\n");

$resourcestable = 'resources';
// Datensatz mit übergebener ID löschen
$DB->delete_records_select($resourcestable,"id ='".$resID."'", $params=null);

//Erfolgsmeldung
$message = "Ressource mit dem Namen ".$resName." und der ID ".$resID." ist gelöscht.";

echo $message;
echo nl2br("\n");
echo nl2br("\n");
echo nl2br("\n");

//Funktionstaste zum Fortfahren definieren
echo $OUTPUT->single_button(new moodle_url('../apeinsdrei/view.php', array('id' => $cm->id)), 'ok');

//Finish
echo $OUTPUT->footer();
?>
