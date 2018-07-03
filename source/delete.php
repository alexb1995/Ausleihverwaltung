<?php
	// Login
	require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
	require_once(dirname(__FILE__).'/lib.php');
	require_once(dirname(__FILE).'/locallib.php');

	$id = optional_param('id', 0, PARAM_INT);	// Course_module ID, or
	$n = optional_param('n', 0, PARAM_INT);		// ... apsechseins instance ID - should be named as first character of the module

	if ($id) {
		$cm 			= get_coursemodule_from_id('apsechseins', $id, 0, false, MUST_EXIST);
		$course 		= $DB->get_record('course', array('id' => $cm->instance), '*', MUST_EXIST);
		$apsechseins	= $DB->get_record('apsechseins', array('id' => $cm->instance), '*', MUST_EXIST);
	} else if ($n) {
		$cm 			= get_coursemodule_from_instance('apsechseins', $apsechseins->id, $course->id, false, MUST_EXIST);
		$course 		= $DB->get_record('course', array('id' => $apsechseins->course), '*', MUST_EXIST);
		$apsechseins 	= $DB->get_record('apsechseins', array('id' => $n), '*', MUST_EXIST);
	} else {
		error('You must specify a course_module ID or an instance ID');
	};

	require_login($course, true, $cm);

	$event = \mod_apsechseins\event\course_module_viewed::create(array(
		'objectid' => $PAGE->cm->instance,
		'context' => $PAGE->context
	));
	$event->add_record_snapshot('course', $PAGE->course);
	$event->add_record_snapshot($PAGE->cm->modname, $apsechseins);
	$event->trigger;

	$PAGE->set_url('/mod/apsechseins/delete.php', array('id' => $cm->id, 'resourceid' => $_GET['resourceid']));
	$PAGE->set_title(format_string($apsechseins->name));
	$PAGE->set_heading(format_string($course->fullname));

	echo $OUTPUT->header();

	$strName = "Vermerk aus der Datenbank löschen";
	echo $OUTPUT->heading($strName);

	// Finish the page
	echo $OUTPUT->footer();
?>