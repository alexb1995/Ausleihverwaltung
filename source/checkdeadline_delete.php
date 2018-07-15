<?php
/*LOGIN*/
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... checkdeadline instance ID - it should be named as the first character of the module.

if ($id) {
    $cm           = get_coursemodule_from_id('ausleihverwaltung', $id, 0, false, MUST_EXIST);
    $course       = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $ausleihverwaltung  = $DB->get_record('ausleihverwaltung', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $ausleihverwaltung  = $DB->get_record('ausleihverwaltung', array('id' => $n), '*', MUST_EXIST);
    $course       = $DB->get_record('course', array('id' => $ausleihverwaltung->course), '*', MUST_EXIST);
    $cm           = get_coursemodule_from_instance('ausleihverwaltung', $ausleihverwaltung->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
};

require_login($course, true, $cm);

$event = \mod_ausleihverwaltung\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $ausleihverwaltung);
$event->trigger();

/*PAGE setzen*/
$PAGE->set_url('/mod/ausleihverwaltung/checkdeadline_delete.php', array('id' => $cm->id,'responsibleid' => $_GET['responsibleid']));
$PAGE->set_title(format_string($ausleihverwaltung->name));
$PAGE->set_heading(format_string($course->fullname));

// Hier beginnt die Ausgabe
echo $OUTPUT->header();

$strName = "Verantwortlichen löschen";
echo $OUTPUT->heading($strName);
echo nl2br("\n");
echo nl2br("\n");

$responsibleID = $_GET['responsibleid']; //Wird von View-PHP mit dem Delete-Link übergeben
$sql= 'SELECT dudesname FROM {ausleihverwaltung_resp} WHERE id ='.$responsibleID.';';
$responsibleDude = $DB->get_record_sql($sql, array($responsibleID));
$responsibleName = $responsibleDude->dudesname;

echo $message = "Willst du den Verantwortlichen ".$responsibleName. " löschen?";
echo nl2br("\n");
echo nl2br("\n");
echo nl2br("\n");

//Funktionstasten zum Abbrechen und Fortfahren
echo $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/checkdeadline_view.php', array('id' => $cm->id)), 'Abbrechen');
echo html_writer::link(new moodle_url('../ausleihverwaltung/checkdeadline_deleteaccept.php', array('id' => $cm->id, 'responsibleID' => $responsibleID, 'responsibleName'=> $responsibleName)), 'Bestätigen', array('class' => 'btn btn-secondary'));

//FINISH
echo $OUTPUT->footer();
?>