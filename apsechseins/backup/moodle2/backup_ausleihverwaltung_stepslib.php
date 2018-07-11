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
<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/backup_ausleihverwaltung_stepslib.php
 * Define all the backup steps that will be used by the backup_ausleihverwaltung_activity_task
 *
 * @package   mod_ausleihverwaltung
=======
 * Define all the backup steps that will be used by the backup_ausleihverwaltung_activity_task
 *
 * @package   mod_ausleihverwaltung
>>>>>>> ausleihverwaltung:source/backup/moodle2/backup_ausleihverwaltung_stepslib.php
 * @category  backup
 * @copyright 2016 Your Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/backup_ausleihverwaltung_stepslib.php
 * Define the complete ausleihverwaltung structure for backup, with file and id annotations
 *
 * @package   mod_ausleihverwaltung
=======
 * Define the complete ausleihverwaltung structure for backup, with file and id annotations
 *
 * @package   mod_ausleihverwaltung
>>>>>>> ausleihverwaltung:source/backup/moodle2/backup_ausleihverwaltung_stepslib.php
 * @category  backup
 * @copyright 2016 Your Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/backup_ausleihverwaltung_stepslib.php
class backup_ausleihverwaltung_activity_structure_step extends backup_activity_structure_step {
=======
class backup_ausleihverwaltung_activity_structure_step extends backup_activity_structure_step {
>>>>>>> ausleihverwaltung:source/backup/moodle2/backup_ausleihverwaltung_stepslib.php

    /**
     * Defines the backup structure of the module
     *
     * @return backup_nested_element
     */
    protected function define_structure() {

        // Get know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/backup_ausleihverwaltung_stepslib.php
        // Define the root element describing the ausleihverwaltung instance.
        $ausleihverwaltung = new backup_nested_element('ausleihverwaltung', array('id'), array(
=======
        // Define the root element describing the ausleihverwaltung instance.
        $ausleihverwaltung = new backup_nested_element('ausleihverwaltung', array('id'), array(
>>>>>>> ausleihverwaltung:source/backup/moodle2/backup_ausleihverwaltung_stepslib.php
            'name', 'intro', 'introformat', 'grade'));

        // If we had more elements, we would build the tree here.

        // Define data sources.
<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/backup_ausleihverwaltung_stepslib.php
        $ausleihverwaltung->set_source_table('ausleihverwaltung', array('id' => backup::VAR_ACTIVITYID));
=======
        $ausleihverwaltung->set_source_table('ausleihverwaltung', array('id' => backup::VAR_ACTIVITYID));
>>>>>>> ausleihverwaltung:source/backup/moodle2/backup_ausleihverwaltung_stepslib.php

        // If we were referring to other tables, we would annotate the relation
        // with the element's annotate_ids() method.

        // Define file annotations (we do not use itemid in this example).
<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/backup_ausleihverwaltung_stepslib.php
        $ausleihverwaltung->annotate_files('mod_ausleihverwaltung', 'intro', null);

        // Return the root element (ausleihverwaltung), wrapped into standard activity structure.
        return $this->prepare_activity_structure($ausleihverwaltung);
=======
        $ausleihverwaltung->annotate_files('mod_ausleihverwaltung', 'intro', null);

        // Return the root element (ausleihverwaltung), wrapped into standard activity structure.
        return $this->prepare_activity_structure($ausleihverwaltung);
>>>>>>> ausleihverwaltung:source/backup/moodle2/backup_ausleihverwaltung_stepslib.php
    }
}
