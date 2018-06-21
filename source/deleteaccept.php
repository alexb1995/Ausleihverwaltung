<?php

/* ALLES FÜR DEN PAGE STYLE*/
/*LOGIN*/
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
$resID = $_GET['resourceid'];
$resName = $_GET['resname'];
/*PAGE SETZEN*/
$PAGE->set_url('/mod/apeinsvier/deleteaccept.php', array('id' => $cm->id,'resourceid' => $resID));
$PAGE->set_title(format_string($apeinsvier->name));
echo nl2br("\n");
$PAGE->set_heading(format_string($course->fullname));

// Output starts here.
echo $OUTPUT->header();
echo nl2br("\n");
$strName = "Löschen erfolgreich";
echo $OUTPUT->heading($strName);
echo nl2br("\n");
//$sql= 'SELECT name FROM {resources} WHERE id ='.$resID.';';
//$resource = $DB->get_record_sql($sql, array($resID));
//$delete = $DB->delete_records('resources', array('id'=$resID));
//$DB->delete_records('resources', array('id'>=28));

$resourcestable = 'resources';
/*
$select1 = ;
$params = array();
*/
//$boolean = 
$DB->delete_records_select($resourcestable,"id ='".$resID."'", $params=null);
//$resName = $resource->name;



//$outputstring = "Willst du die Ressource mit dem Namen".$resName." und der ID ".$resID." löschen?";

//echo "Willst du die Ressource mit dem Namen ".$resName." und der ID ".$resID." löschen?";
//echo nl2br("\n");
//echo html_writer:: empty_tag('input',array('type'=>'button','name'=>'reject','value'=>'abbrechen', 'class'=>"button"));
//echo html_writer:: empty_tag('input',array('type'=>'submit','name'=>'accept','value'=>'bestaetigen', 'class'=>"button"));

$message = "Ressource mit dem Namen ".$resName." und der ID ".$resID." ist gelöscht.";
echo $message;
echo nl2br("\n");
echo nl2br("\n");
echo nl2br("\n");
//echo $OUTPUT->confirm($message,new moodle_url('../apeinsvier/edit.php', array('id' => $cm->id)),new moodle_url('../apeinsvier/view.php', array('id' => $cm->id)));
//echo $OUTPUT->continue_button(new moodle_url('../apeinsvier/view.php', array('id' => $cm->id)));
echo $OUTPUT->single_button(new moodle_url('../apeinsvier/view.php', array('id' => $cm->id)), 'ok');

echo $OUTPUT->footer();

?>
