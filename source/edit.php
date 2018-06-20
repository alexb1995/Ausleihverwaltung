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

$strName = "Ressource bearbeiten";
echo $OUTPUT->heading($strName);
echo nl2br("\n");
echo nl2br("\n");


//hart gesetzt
// RAUS NEHMEN
//$resID = $_GET['resourceid'];
//if($resID==null)
$resID = 12;    
$sql= 'SELECT * FROM {resources} WHERE id ='.$resID.';';
$resource = $DB->get_record_sql($sql, array($resID));
$resName = $resource->name;
$resDescription = $resource->description;
$resSerNumber= $resource->serialnumber;
$resInvNumber= $resource->inventorynumber;
$resComment = $resource->comment;
$resStatus = $resource->status;
$resAmount = $resource->amount;
$resType = $resource->type;
$resMainCategory = $resource->maincategory;
$resSubCategory = $resource->subcategory;

echo $message = "Bitte gebe die neuen Daten für die Ressource mit dem Namen ".$resName." und der ID ".$resID." ein oder kehre mit 'abbrechen' zurück";

echo nl2br("\n");
echo nl2br("\n");
echo nl2br("\n");

// Implement form for user
require_once(dirname(__FILE__).'/forms/resourceform.php');

$mform = new resourcehtml_form ( null, array('name'=>$resName, 'description'=>$resDescription,'serialnumber'=>$resSerNumber,
'inventorynumber'=>$resInvNumber,'comment'=>$resComment, 'status'=>$resStatus, 'amount'=>$resAmount, 'type'=>$resType,
'maincategory'=>$resMainCategory, 'subcategory'=>$resSubCategory) );

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
    error_log("TEST FROM DIRECTLY AFTER SUBMIT");
    $fm_name = $fromform->name;
    $fm_description = $fromform->description;
    $fm_serialnumber= $fromform->serialnumber;
    $fm_inventorynumber = $fromform->inventorynumber;
    $fm_comment = $fromform->comment;
    $fm_status = $fromform->status;
    $fm_amount = $fromform->amount;
    $fm_type = $fromform->type;
    $fm_maincategory = $fromform->maincategory;
    $fm_subcategory = $fromform->subcategory;

    echo $fm_name;

  //In this case you process validated data. $mform->get_data() returns data posted in form.
  //Creating instance of relevant API modules
  create_api_instances();
  $process_definition_id = apeinsvier_get_process_definition_id("editresource");
  //error_log("PROCESS DEFINITION ID IS: " . $process_definition_id);
  $process_instance_id = apeinsvier_start_process($process_definition_id, "myBusinessKeyJaz");
  //error_log("PROCESS INSTANCE ID IS: " . $process_instance_id);
  sleep(3);
  //error_log("WAKEY WAKEY, BOYS AND GIRLS");
  $taskid = apeinsvier_check_for_input_required($process_instance_id);
  //error_log("TASK ID IS: " . $taskid);
  if ($taskid != null) {
    //error_log("EXECUTION OF TASK RESPONSE");

    $fm_name = $fromform->name;
    $fm_description = $fromform->description;
    $fm_serialnumber= $fromform->serialnumber;
    $fm_inventorynumber = $fromform->inventorynumber;
    $fm_comment = $fromform->comment;
    $fm_status = $fromform->status;
    $fm_amount = $fromform->amount;
    $fm_type = $fromform->type;
    $fm_maincategory = $fromform->maincategory;
    $fm_subcategory = $fromform->subcategory;

    $result = apeinsvier_answer_input_required_resources($taskid, $process_definition_id, $fm_name, $fm_description, $fm_serialnumber, $fm_inventorynumber,$fm_comment,$fm_status,$fm_amount,$fm_type,$fm_maincategory,$fm_subcategory);
    //if result = valid, mach Update

    $record = new stdClass();
    $record->id=$resID;
    $record->name = $fm_name;
    $record->description = $fm_description;
    $record->serialnumber =$fm_serialnumber;
    $record->inventorynumber=$fm_inventorynumber ;
    $record->comment=  $fm_comment;
    $record->status=$fm_status;
    $record->amount= $fm_amount;
    $record->type=$fm_type;
    $record->maincategory=$fm_maincategory;
    $record->subcategory=$fm_subcategory;
    //$DB->update_record($table, $record, $bulk=false);
    $DB->update_record('resources',$record,$bulk=false);
    //error_log("INPUT SEND RESULT IS: " . $result);
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

echo $OUTPUT->single_button(new moodle_url('../apeinsvier/view.php', array('id' => $cm->id)), 'abbrechen');
echo html_writer::link(new moodle_url('../apeinsvier/deleteaccept.php', array('id' => $cm->id, 'resourceid' => $resID, 'resname'=> $resName)), 'speichern', array('class' => 'btn btn-secondary'));
echo $OUTPUT->footer();


?>
