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
 * Prints a particular instance of ausleihantrag
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_ausleihverwaltung
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace ausleihantrag with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... ausleihantrag instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('ausleihantrag', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $ausleihantrag  = $DB->get_record('ausleihantrag', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $ausleihantrag  = $DB->get_record('ausleihantrag', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $ausleihantrag->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('ausleihantrag', $ausleihantrag->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_ausleihverwaltung\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $ausleihantrag);
$event->trigger();


// Um Tabelle >>resources<< zu belegen
/*
$record = new stdClass();
$record->name         = 'handy';
$record->description = 'dasd';
$record->serialnumber        = 'serial12';
$record->inventorynumber = 'invent123';
$record->comment        = 'Comment this Comment thisComment thisComment thisComment thisComment thisComment thisComment thisComment thisComment thisComment thisComment thisComment thisComment thisComment this';
$record->status = 0;
$record->amount         = 2;
$record->type = 0;
$record->maincategory    = "Handy";
$record->subcategory = "sub";
$record->defect = "damage is done";

$DB->insert_record('ausleihantrag_resources', $record, $returnid=false, $bulk=false);

/*
$record1->name         = 'iPhone';
$record1->description = 'beschde';
$record1->serialnumber        = 'serial14';
$record1->inventorynumber = 'invent567';
$record1->comment        = 'Comment that';
$record1->status = 3;
$record1->amount         = 4;
$record1->type = 1;
$record1->maincategory    = "Apple";
$record1->subcategory = "phone";
$DB->insert_record('ausleihantrag_resources', $record1, $returnid=false, $bulk=false);

$record2->name         = 'Mein iPhone';
$record2->description = 'beschde';
$record2->serialnumber        = 'blablub';
$record2->inventorynumber = 'invent567';
$record2->comment        = 'Comment that';
$record2->status = 3;
$record2->amount         = 4;
$record2->type = 1;
$record2->maincategory    = "Apple";
$record2->subcategory = "phone";
$DB->insert_record('ausleihantrag_resources', $record1, $returnid=false, $bulk=false);
*/

/* PAGE belegen*/
$PAGE->set_url('/mod/ausleihverwaltung/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($ausleihantrag->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('ausleihantrag-'.$somevar);
 */

// Hier beginnt die Ausgabe
echo $OUTPUT->header();

$strName = "Ausleihantrag stellen";
echo $OUTPUT->heading($strName);

echo $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/ausleihantrag_view.php', array('id' => $cm->id)), 'Ausleihantrag stellen');

// Conditions to show the intro can change to look for own settings or whatever.
if ($ausleihantrag->intro) {
    echo $OUTPUT->box(format_module_intro('ausleihantrag', $ausleihantrag, $cm->id), 'generalbox mod_introbox', 'ausleihantragintro');
}

$strName = "Ausleihen-Übersicht";
echo $OUTPUT->heading($strName);

echo $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/checkdeadline_view.php', array('id' => $cm->id)), 'Ausleihübersicht anzeigen');


$strName = "Ressourcen-Übersicht";
echo $OUTPUT->heading($strName);

$attributes = array();
// Alle Datensätze aus der DB-Tabelle >>resources<< abfragen.
$resource = $DB->get_records('av_resources');

$table = new html_table();
$table->head = array('ID','Name', 'Beschreibung', 'Seriennummer', 'Inventarnummer', 'Kommentar', 'Status', 'Menge', 'Typ', 'Hauptkategorie', 'Subkategorie', 'Schaden', 'Bearbeiten', 'Löschen');

//Für jeden Datensatz
foreach ($resource as $res) {
    $id = $res->id;
    $name = $res->name;
    $description = $res->description;
    $serialnumber = $res->serialnumber;
    $inventorynumber = $res->inventorynumber;
    $comment = $res->comment;
    $status = $res->status;
    $amount = $res->amount;
    $type = $res->type;
    $maincategory = $res->maincategory;
    $subcategory = $res->subcategory;
    $defect = $res->defect;
//Link zum Bearbeiten der aktuellen Ressource in foreach-Schleife setzen
  
    $htmlLink = html_writer::link(new moodle_url('../ausleihverwaltung/resources_edit.php', array('id' => $cm->id, 'resourceid' => $res->id)), 'Edit', $attributes=null);
//Analog: Link zum Löschen...
    $htmlLinkDelete = html_writer::link(new moodle_url('../ausleihverwaltung/resources_delete.php', array('id' => $cm->id, 'resourceid' => $res->id)), 'Delete', $attributes=null);

//Daten zuweisen an HTML-Tabelle
    $table->data[] = array($id, $name, $description, $serialnumber, $inventorynumber, $comment, $status, $amount, $type, $maincategory, $subcategory, $defect, $htmlLink, $htmlLinkDelete);
}
//Tabelle ausgeben
echo html_writer::table($table);

// Finish the page.
echo $OUTPUT->footer();
