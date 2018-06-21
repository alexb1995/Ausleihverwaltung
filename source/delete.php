<?php

/* ALLES FÜR DEN PAGE STYLE*/
/*LOGIN*/
require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');
//require_once 'libraries/jQuery.php';
html_writer::script('../apeinsvier/jquery-3.3.1.min.js');

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

/*PAGE SETZEN*/
$PAGE->set_url('/mod/apeinsvier/delete.php', array('id' => $cm->id,'resourceid' => $_GET['resourceid']));
$PAGE->set_title(format_string($apeinsvier->name));
$PAGE->set_heading(format_string($course->fullname));

// Output starts here.
echo $OUTPUT->header();

$strName = "Ressource löschen";
echo $OUTPUT->heading($strName);


echo nl2br("\n");
//echo $_GET['id'];

echo nl2br("\n");
//echo $_GET['resourceid'];

$resID = $_GET['resourceid'];
$sql= 'SELECT name FROM {resources} WHERE id ='.$resID.';';
$resource = $DB->get_record_sql($sql, array($resID));
$resName = $resource->name;

//$outputstring = "Willst du die Ressource mit dem Namen".$resName." und der ID ".$resID." löschen?";

/*
$jsString = "$(document).ready(function(){
    $('.button').click(function(){
        var clickBtnValue = $(this).val();

		alert('Hi '+ clickBtnValue + '!');
    });

});";
*/
//html_writer::script($jsString);

//echo "Willst du die Ressource mit dem Namen ".$resName." und der ID ".$resID." löschen?";
//echo nl2br("\n");
//echo html_writer:: empty_tag('input',array('type'=>'button','name'=>'reject','value'=>'abbrechen', 'class'=>"button"));
//echo html_writer:: empty_tag('input',array('type'=>'submit','name'=>'accept','value'=>'bestaetigen', 'class'=>"button"));

//$htmlLink = html_writer::link(new moodle_url('../apeinsvier/deleteaccept.php', array('id' => $cm->id, 'resourceid' => $resID)), 'DeleteAccept', array('class' => 'btn btn-secondary'));

echo $message = "Willst du die Ressource mit dem Namen ".$resName." und der ID ".$resID." löschen?";
//echo $OUTPUT->confirm($message, $htmlLink, new moodle_url('../apeinsvier/view.php', array('id' => $cm->id, 'resourceid' => $resID)));
//array('id' => $cm->id, 'resourceid' => $resID)

/*
echo $OUTPUT->box_start();
echo $message = "Willst du die Ressource mit dem Namen ".$resName." und der ID ".$resID." löschen?";
echo html_writer::link(new moodle_url('../apeinsvier/deleteaccept.php', array('id' => $cm->id, 'resourceid' => $resID)), 'DeleteAccept', array('class' => 'btn btn-secondary'));
echo $OUTPUT->box_end();
*/
echo nl2br("\n");
echo nl2br("\n");
echo nl2br("\n");
//echo html_writer::link(new moodle_url('../apeinsvier/deleteaccept.php', array('id' => $cm->id, 'resourceid' => $resID)), 'DeleteAccept', array('class' => 'btn btn-secondary'));
echo $OUTPUT->single_button(new moodle_url('../apeinsvier/view.php', array('id' => $cm->id)), 'abbrechen');
echo html_writer::link(new moodle_url('../apeinsvier/deleteaccept.php', array('id' => $cm->id, 'resourceid' => $resID, 'resname'=> $resName)), 'bestätigen', array('class' => 'btn btn-secondary'));
//echo $OUTPUT->single_button('https://moodle.ganymed.me/mod/apeinsvier/deleteaccept.php?id=27&resourceid=1', 'blablub', $method='post', array $params);
echo $OUTPUT->footer();


?>
