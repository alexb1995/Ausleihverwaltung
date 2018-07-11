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

/*PAGE SETZEN*/
$PAGE->set_url('/mod/apeinsdrei/edit.php', array('id' => $cm->id,'resourceid' => $_GET['resourceid']));
$PAGE->set_title(format_string($apeinsdrei->name));
$PAGE->set_heading(format_string($course->fullname));

/* Ab hier beginnt der Output */
echo $OUTPUT->header();
$strName = "Ressource bearbeiten";
echo $OUTPUT->heading($strName);

$url = $PAGE->url;
$strUrl = $url.'';

/* Grosse Verzweigung -> prüfe, ob erster oder zweiter Seitenaufbau*/

// erster Seitenaufbau -> Formular erstellen und mit Werten der ausgewählten Ressource vorbelegen
//// bei dem ersten Aufbau sind Kursid und Ressorucenid noch in der Moodle-Url gesetzt, hiernach prüfen
// zweiter Seitenaufbau nachdem Formular abgesendet wurde, wird die DB aktualisiert und Erfoolg ausgegeben
if(strpos($strUrl, 'resourceid=')==true){
    //Erster Durchlauf
    $resID = $_GET['resourceid'];
    $sql= 'SELECT * FROM {apeinsvier_resources} WHERE id ='.$resID.';';
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
    $resDefect = $resource->defect;
    echo $message = "Bitte gebe die neuen Daten für die Ressource mit dem Namen ".$resName." und der ID ".$resID." ein oder kehre mit 'abbrechen' zurück";
    echo nl2br("\n");
    echo nl2br("\n");
    echo nl2br("\n");

    // Formular aufbauen und mit DB-Werten vorbelegen, hierfür in den Konstruktur übergeben
    require_once(dirname(__FILE__).'/forms/resourceform.php');
    $mform = new resourcehtml_form ( null, array('name'=>$resName, 'description'=>$resDescription,'serialnumber'=>$resSerNumber,
    'inventorynumber'=>$resInvNumber,'comment'=>$resComment, 'status'=>$resStatus, 'amount'=>$resAmount, 'type'=>$resType,
    'maincategory'=>$resMainCategory, 'subcategory'=>$resSubCategory, 'defect'=>$resDefect) );
    //Formulardaten verarbeiten
    if ($fromform = $mform->get_data()) {
        error_log("TEST FROM DIRECTLY AFTER SUBMIT");
        $fm_resid = $fromform->resourceid;
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
        $fm_defect = $fromform->defect;

        /* Hier koennte man Activiti einbinden
        //Creating instance of relevant API modules
        create_api_instances();
        $process_definition_id = apeinsdrei_get_process_definition_id("meisterkey"); //key aus dem Prozessmodel
        //error_log("PROCESS DEFINITION ID IS: " . $process_definition_id);
        $process_instance_id = apeinsdrei_start_process($process_definition_id, 'businesskey');
        //error_log("PROCESS INSTANCE ID IS: " . $process_instance_id);
        sleep(3);
        $taskid = apeinsdrei_check_for_input_required($process_instance_id);
        //error_log("TASK ID IS: " . $taskid);
        if ($taskid != null) {
            //error_log("EXECUTION OF TASK RESPONSE");
        */
        //Formwerte in Variablen speichern
        $fm_resid = $fromform->resourceid;
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
        $fm_defect = $fromform->defect;

        /*Activit*/
        //$result = apeinsdrei_answer_input_required_resources($taskid, $process_definition_id, $fm_name, $fm_description, $fm_serialnumber, $fm_inventorynumber,$fm_comment,$fm_status,$fm_amount,$fm_type,$fm_maincategory,$fm_subcategory);
        //neue anonyme Klasse aufbauen und instanziieren, Formvariablen als Eigenschaften belegen
        $record = new stdClass();
        $record->id=$fm_resid;
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
        $record->defect=$fm_defect;
    } 
    else {
        // falls die Daten des Formulars nicht validiert wurden oder für die erste Anzeige des Formulars
        
        $formdata = array('id' => $id, 'resourceid' => $resID); // Moodle braucht die Moodle-Kursid, diese war hidden-input im Formular und wird hier gesetzt
        $mform->set_data($formdata);
        //Formular anzeigen
        $mform->display();
        //error_log("TEST FROM AFTER DISPLAY");
    }
    echo $OUTPUT->single_button(new moodle_url('../apeinsdrei/view.php', array('id' => $cm->id)), 'abbrechen');

}

else{
    //zweiter Durchlauf

    require_once(dirname(__FILE__).'/forms/resourceform.php');
    $mform = new resourcehtml_form ();

    //Formulardaten verarbeiten
    if ($fromform = $mform->get_data()) {
        error_log("TEST FROM DIRECTLY AFTER SUBMIT");
        $fm_resid = $fromform->resourceid;
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
        $fm_defect = $fromform->defect;

        /* Hier koennte man Activiti einbinden
        //Creating instance of relevant API modules
        create_api_instances();
        $process_definition_id = apeinsdrei_get_process_definition_id("meisterkey"); //key aus dem Prozessmodel
        //error_log("PROCESS DEFINITION ID IS: " . $process_definition_id);
        $process_instance_id = apeinsdrei_start_process($process_definition_id, 'businesskey');
        //error_log("PROCESS INSTANCE ID IS: " . $process_instance_id);
        sleep(3);
        $taskid = apeinsdrei_check_for_input_required($process_instance_id);
        //error_log("TASK ID IS: " . $taskid);
        if ($taskid != null) {
            //error_log("EXECUTION OF TASK RESPONSE");
        */

        $fm_resid = $fromform->resourceid;
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
        $fm_defect = $fromform->defect;
        /*Activiti*/
        // $result = apeinsdrei_answer_input_required_resources($taskid, $process_definition_id, $fm_name, $fm_description, $fm_serialnumber, $fm_inventorynumber,$fm_comment,$fm_status,$fm_amount,$fm_type,$fm_maincategory,$fm_subcategory);
        
        $record = new stdClass();
        $record->id=$fm_resid;
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
        $record->defect=$fm_defect;
        //DB-Update: Tabelle: >>resources<<, Record-Objekt, kein Bulk Update
        $DB->update_record('apeinsvier_resources',$record,$bulk=false); 
        echo "Die Ressource wurde mit der ID ".$fm_resid." wurde erfolgreich aktualisiert.";
    }

    else {

    // falls die Daten des Formulars nicht validiert wurden oder für die erste Anzeige des Formulars
    $formdata = array('id' => $id); // Moodle braucht die Moodle-Kursid, diese war hidden-input im Formular und wird hier gesetzt/*, 'resourceid' => $resID);*/
    $mform->set_data($formdata);
    //Formular anzeigen
    $mform->display();
    //error_log("TEST FROM AFTER DISPLAY");
    }
    echo nl2br("\n");
    echo $OUTPUT->single_button(new moodle_url('../apeinsdrei/view.php', array('id' => $cm->id)), 'ok');
}

echo nl2br("\n");
echo nl2br("\n");
echo $OUTPUT->footer();
?>

