<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Internal library of functions for module ausleihverwaltung
 *
 * All the ausleihverwaltung specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package    mod_ausleihverwaltung
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/lib/autoload.php');

defined('MOODLE_INTERNAL') || die();

$processDefinitionsApiInstance = null;
$processInstancesApiInstance = null;
$tasksApiInstance = null;
$formsApiInstance = null;

function create_api_instances() {
	// Configure HTTP basic authorization: basicAuth
	$config = Swagger\Client\Configuration::getDefaultConfiguration()
		->setUsername('kermit')
		->setPassword('kermit');

	global $engineApiInstance;
	$engineApiInstance = new Swagger\Client\Api\EngineApi(
		// If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
		// This is optional, `GuzzleHttp\Client` will be used as default.
		new GuzzleHttp\Client(),
		$config
	);

	global $processDefinitionsApiInstance;
	$processDefinitionsApiInstance = new Swagger\Client\Api\ProcessDefinitionsApi(
		// If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
		// This is optional, `GuzzleHttp\Client` will be used as default.
		new GuzzleHttp\Client(),
		$config
	);

	global $processInstancesApiInstance;
	$processInstancesApiInstance = new Swagger\Client\Api\ProcessInstancesApi(
		// If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
		// This is optional, `GuzzleHttp\Client` will be used as default.
		new GuzzleHttp\Client(),
		$config
	);

	global $tasksApiInstance;
	$tasksApiInstance = new Swagger\Client\Api\TasksApi(
		// If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
		// This is optional, `GuzzleHttp\Client` will be used as default.
		new GuzzleHttp\Client(),
		$config
	);

	global $formsApiInstance;
	$formsApiInstance = new Swagger\Client\Api\FormsApi(
		// If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
		// This is optional, `GuzzleHttp\Client` will be used as default.
		new GuzzleHttp\Client(),
		$config
	);
}

function ausleihverwaltung_do_something_useful(array $things) {
    return new stdClass();
}

function ausleihverwaltung_get_process_definition_id($processKey) {
	global $processDefinitionsApiInstance;

	$version = null; // int | Only return process definitions with the given version.
	$name = null; // string | Only return process definitions with the given name.
	$name_like = null; // string | Only return process definitions with a name like the given name.
	$key_like = null; // string | Only return process definitions with a name like the given key.
	$resource_name = null; // string | Only return process definitions with the given resource name.
	$resource_name_like = null; // string | Only return process definitions with a name like the given resource name.
	$category = null; // string | Only return process definitions with the given category.
	$category_like = null; // string | Only return process definitions with a category like the given name.
	$category_not_equals = null; // string | Only return process definitions which donï¿½t have the given category.
	$deployment_id = null; // string | Only return process definitions with the given category.
	$startable_by_user = null; // string | Only return process definitions which are part of a deployment with the given id.
	$latest = "true"; // bool | Only return the latest process definition versions. Can only be used together with key and keyLike parameters, using any other parameter will result in a 400-response.
	$suspended = null; // bool | If true, only returns process definitions which are suspended. If false, only active process definitions (which are not suspended) are returned.
	$sort = "version"; // string | Property to sort on, to be used together with the order.
	try {
		$result = $processDefinitionsApiInstance->getProcessDefinitions($version, $name, $name_like, $processKey, $key_like, $resource_name, $resource_name_like, $category, $category_like, $category_not_equals, $deployment_id, $startable_by_user, $latest, $suspended, $sort);
        $process_definition_id = "meisterkey:1:10870"; //fix gesetzt, sollte bei Activit-Integration dynamisch aufgebaut werden
		return $process_definition_id;
	} catch (Exception $e) {
		echo 'Exception when calling ProcessDefinitionsApi->getProcessDefinitions: ', 	$e->getMessage(), PHP_EOL;
		return null;
	}
}

function ausleihverwaltung_start_process($process_definition_id, $business_key) {
	global $processInstancesApiInstance;

	$requestArray = array(
		'process_definition_id' => $process_definition_id,
		'business_key' => $business_key
	);
	$body = new \Swagger\Client\Model\ProcessInstanceCreateRequest($requestArray); // \Swagger\Client\Model\ProcessInstanceCreateRequest |

	// attempt to create instance for process
	try {
		$result = $processInstancesApiInstance->createProcessInstance($body);
		// get instance ID
		$process_instance_id = $result->getId();
		print_r($process_instance_id);
		return $process_instance_id;

	} catch (Exception $e) {
		echo 'Exception when calling ProcessInstancesApi->createProcessInstance: ', $e->getMessage(), PHP_EOL;
		return null;
	}
}

function ausleihverwaltung_check_for_input_required($process_instance_id) {
	global $tasksApiInstance;
	try {
		$result = $tasksApiInstance->getTasks(null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, $process_instance_id, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
		// print("PRINT TASKS CONNECTED TO PROCESS INSTANCE");
		$task_id = $result['data'][0]->id;
		$task_name = $result['data'][0]->name;
		$taskDefinitionKey = $result['data'][0]->taskDefinitionKey;
		print_r($result['data'][0]);
		return $task_id;

	} catch (Exception $e) {
		// echo 'Exception when calling TasksApi->getTasks: ', $e->getMessage(), PHP_EOL;
		echo "Nope, not yet.";
		return null;
	}
}


//TODO J&C
function ausleihverwaltung_answer_input_required_resources($task_id, $process_definition_id,
$resName, $resDescription, $resSerNumber, $resInvNumber,$resComment,$resStatus,$resAmount,$resType,$resMainCategory,$resSubCategory) {
	global $formsApiInstance;

	$formArray = array(
		'action' => 'submit',
		'task_id' => $task_id,
		'process_definition_id' => $process_definition_id,
		'properties' => array(
			array(
				'id' => 'name',
				'value' => $resName
			),
			array(
				'id' => 'description',
				'value' => $resDescription
			),
			array(
				'id' => 'serialnumber',
				'value' => $resSerNumber
			),
			array(
				'id' => 'inventorynumber',
				'value' => $resInvNumber
			),
			array(
				'id' => 'comment',
				'value' => $resComment
			),
			array(
				'id' => 'status',
				'value' => $resStatus
			),
			array(
				'id' => 'amount',
				'value' => $resAmount
			),
			array(
				'id' => 'type',
				'value' => $resType
			),
			array(
				'id' => 'maincategory',
				'value' => $resMainCategory
			),
			array(
				'id' => 'subcategory',
				'value' => $resSubCategory
			)
		)
	);

	$body = new \Swagger\Client\Model\SubmitFormRequest($formArray); // \Swagger\Client\Model\SubmitFormRequest |

	try {
		$result = $formsApiInstance->submitForm($body);
		//print_r($result);
		return $result;
	} catch (Exception $e) {
		echo 'Exception when calling FormsApi->submitForm: ', $e->getMessage(), PHP_EOL;
		return null;
	}
}

function ausleihverwaltung_answer_input_required($task_id, $process_definition_id, $value1, $value2) {
	global $formsApiInstance;

	$formArray = array(
		action => "submit",
		task_id => $task_id,
		process_definition_id => $process_definition_id,
		properties => array(
			array(
				id => new_property_1,
				value => $value1
			),
			array(
				id => new_property_2,
				value => $value2
			)
		)
	);

	$body = new \Swagger\Client\Model\SubmitFormRequest($formArray); // \Swagger\Client\Model\SubmitFormRequest |

	try {
		$result = $formsApiInstance->submitForm($body);
		print_r($result);
		return $result;
	} catch (Exception $e) {
		echo 'Exception when calling FormsApi->submitForm: ', $e->getMessage(), PHP_EOL;
		return null;
	}
}

function ausleihverwaltung_get_process_instance_status($process_instance_id) {
	global $processInstancesApiInstance;

	try {
		$result = $processInstancesApiInstance->getProcessInstance($process_instance_id);
		print("PRINT INFO ABOUT PROCESS INSTANCE");
		print_r($result);
		return $result;

	} catch (Exception $e) {
		echo 'Exception when calling ProcessInstancesApi->getProcessInstance: ', $e->getMessage(), PHP_EOL;
		return null;
	}
}

require_once($CFG->dirroot.'/lib/moodlelib.php');
require_once($CFG->dirroot.'/config.php');

function mail_to($email, $name, $subject, $message) {
	global $DB;

	$from = new stdClass();
    $from->firstname = 'sWIm15';
    $from->lastname  = '';
    $from->firstnamephonetic = '';
    $from->lastnamephonetic = '';
    $from->middlename = '';
    $from->alternatename = '';
    $from->email     = 'swim15.noreply@gmail.com';
    $from->maildisplay = true;
    $from->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML emails.

	$emailsubject = $subject;
	$emailmessage = $message;

	$user = $DB->get_record('user', ['email' => $email]);

	if (!isset($user) or empty($user['email'])) {
		$user = generate_dummy_user($email, $name);
	}

	$success = email_to_user($user, $from, $emailsubject, $emailmessage);

	return $success;
}

function generate_dummy_user($email, $name = '', $id = -99) {
	$emailuser = new stdClass();
	$emailuser->email = trim(filter_var($email, FILTER_SANITIZE_EMAIL));
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$emailuser->email = '';
		}
	$name = format_text($name, FORMAT_HTML, array('trusted' => false, 'noclean' => false));
	$emailuser->firstname = trim(filter_var($name, FILTER_SANITIZE_STRING));
	$emailuser->lastname = '';
	$emailuser->maildisplay = true;
	$emailuser->mailformat = 1; // 0 (zero) text-only emails, 1 (one) for HTML emails.
	$emailuser->id = $id;
	$emailuser->firstnamephonetic = '';
	$emailuser->lastnamephonetic = '';
	$emailuser->middlename = '';
	$emailuser->alternatename = '';

	return $emailuser;
	}

require_once($CFG->dirroot.'/lib/tcpdf/tcpdf.php');

function prep_leihschein($borrowedid) {

	global $DB;

	$borrowedMeta = $DB->get_record('ausleihverwaltung_borrowed', array('id'=> $borrowedid));
	$borrowedResource = $DB->get_record('ausleihverwaltung_resources', array('id'=>$borrowedMeta->resourceid));

	$today = date("d-m-y");

	$duedateepoch = $borrowedMeta->duedate;
	$duedate = new DateTime("@$duedateepoch");
	$duedate = $duedate->format('d-m-Y');

	//Ressourcentabelle erstellen:
	$table = "<table><tr><th>ID</th><th>Menge</th><th>Artikel</th><th>Anmerkungen</th></tr>";
	$table .= "<tr><td>" . $borrowedResource->id. "</td><td><p>1</p></td><td>" . $borrowedResource->name. "</td><td>" . $borrowedResource->defect. "</td></tr></table>";


    $ausleihantrag = array(
    '%Name' => $borrowedMeta->studentname,
	'%Matrikel' => $borrowedMeta->studentmatrikelnummer,
	'%Email' => $borrowedMeta->studentmailaddress,
	'%Tabelle' => $table,
    '%Rückgabe' => $duedate,
    '%Zweck' => $borrowedMeta->borrowreason,
    '%Datum' => $today,
    '%Bemerkung' => $borrowedMeta->comment
	);

    generate_pdf($ausleihantrag, $borrowedid);
    }

function generate_pdf($replacements, $id) {
	ob_start();
	$leihschein = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false, false);
	$leihschein->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'DHBW Mannheim', 'Digitaler Leihschein');

	$html = file_get_contents("leihschein.html");
	$html = str_replace(array_keys($replacements), $replacements, $html);

	$leihschein->AddPage();

	$leihschein->SetCreator('sWIm15');
	$leihschein->SetAuthor('DHBW Mannheim');
	$leihschein->SetTitle('Digitaler Leihschein');
	$leihschein->SetSubject('');
	$leihschein->writeHTML($html, true, 0, true, true);
	$leihschein->setPrintHeader(false);
	$leihschein->setPrintFooter(false);

	$filename = "leihschein";
	$filename .= $id;
	$filename .= ".pdf";

	ob_clean();
	$leihschein->Output($filename, 'D');
}

function mail_confirm_ausleihantrag($iEmail, $iName, $iAusleihantrag, $iUserrolle){
	//Erwartet wird Email & Namen des Ausleihenden, die Userrolle(student v teacher v editingteacher)und die Nachricht(Ausleihantrag) als String

	global $DB;

	//Deklarierung der Genehmiger
	$EmailLaboringenieur = 'Laboringenieur@dhbw-mannheim.de';
	$EmailStudiengangsleitung ='Studiengangsleitung@dhbw-mannheim.de';

		if (strpos($iUserrolle, 'student') == true){
				//Benachrichtigung Studentische Ausleihe
				mail_to($iEmail,$iName,'Eingang Ihres Ausleihantrags',$iAusleihantrag);
				mail_to($EmailStudiengangsleitung,'Studiengangsleitung','Eingang Ausleihantrag Student',$iAusleihantrag);
				mail_to($EmailLaboringenieur,'Laboringenieur','Eingang Ausleihantrag Student',$iAusleihantrag);
		}
		if(strpos($iUserrolle, 'teacher') == true){
				//Benachrichtigung Dozenten Ausleihe
				mail_to($iEmail,$iName,'Eingang Ihres Ausleihantrags',$iAusleihantrag);
				mail_to($EmailLaboringenieur,'Laboringenieur','Eingang Ausleihantrag Dozenten',$iAusleihantrag);
		}
		else{
			//Benachrichtigung externe Ausleihe
			mail_to($iEmail,$iName,'Eingang Ihres Ausleihantrags',$iAusleihantrag);
			mail_to($EmailLaboringenieur,'Laboringenieur','Eingang eines externen Ausleihantrags',$iAusleihantrag);
		}

	//Persistente Speicherung der Emailadresse und der Nachricht/Ausleihantrags in der DB
	$record = new stdClass();
	$record->email        = $iEmail;
	$record->message = $iAusleihantrag;
	$lastinsertid = $DB->insert_record('ausleihverwaltung_emailantrag', $record, false);
	}
