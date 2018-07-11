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
 * Provides the restore activity task class
 *
<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php
 * @package   mod_ausleihverwaltung
=======
 * @package   mod_ausleihverwaltung
>>>>>>> ausleihverwaltung:source/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php
 * @category  backup
 * @copyright 2016 Your Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php
require_once($CFG->dirroot . '/mod/ausleihverwaltung/backup/moodle2/restore_ausleihverwaltung_stepslib.php');

/**
 * Restore task for the ausleihverwaltung activity module
 *
 * Provides all the settings and steps to perform complete restore of the activity.
 *
 * @package   mod_ausleihverwaltung
=======
require_once($CFG->dirroot . '/mod/ausleihverwaltung/backup/moodle2/restore_ausleihverwaltung_stepslib.php');

/**
 * Restore task for the ausleihverwaltung activity module
 *
 * Provides all the settings and steps to perform complete restore of the activity.
 *
 * @package   mod_ausleihverwaltung
>>>>>>> ausleihverwaltung:source/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php
 * @category  backup
 * @copyright 2016 Your Name <your@email.address>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php
class restore_ausleihverwaltung_activity_task extends restore_activity_task {
=======
class restore_ausleihverwaltung_activity_task extends restore_activity_task {
>>>>>>> ausleihverwaltung:source/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity.
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps() {
        // We have just one structure step here.
<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php
        $this->add_step(new restore_ausleihverwaltung_activity_structure_step('ausleihverwaltung_structure', 'ausleihverwaltung.xml'));
=======
        $this->add_step(new restore_ausleihverwaltung_activity_structure_step('ausleihverwaltung_structure', 'ausleihverwaltung.xml'));
>>>>>>> ausleihverwaltung:source/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php
    }

    /**
     * Define the contents in the activity that must be
     * processed by the link decoder
     */
    static public function define_decode_contents() {
        $contents = array();

<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php
        $contents[] = new restore_decode_content('ausleihverwaltung', array('intro'), 'ausleihverwaltung');
=======
        $contents[] = new restore_decode_content('ausleihverwaltung', array('intro'), 'ausleihverwaltung');
>>>>>>> ausleihverwaltung:source/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php

        return $contents;
    }

    /**
     * Define the decoding rules for links belonging
     * to the activity to be executed by the link decoder
     */
    static public function define_decode_rules() {
        $rules = array();

<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php
        $rules[] = new restore_decode_rule('AUSLEIHVERWALTUNGVIEWBYID', '/mod/ausleihverwaltung/view.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('AUSLEIHVERWALTUNGINDEX', '/mod/ausleihverwaltung/index.php?id=$1', 'course');
=======
        $rules[] = new restore_decode_rule('ausleihverwaltungVIEWBYID', '/mod/ausleihverwaltung/view.php?id=$1', 'course_module');
        $rules[] = new restore_decode_rule('ausleihverwaltungINDEX', '/mod/ausleihverwaltung/index.php?id=$1', 'course');
>>>>>>> ausleihverwaltung:source/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php

        return $rules;

    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php
     * ausleihverwaltung logs. It must return one array
=======
     * ausleihverwaltung logs. It must return one array
>>>>>>> ausleihverwaltung:source/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php
     * of {@link restore_log_rule} objects
     */
    static public function define_restore_log_rules() {
        $rules = array();

<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php
        $rules[] = new restore_log_rule('ausleihverwaltung', 'add', 'view.php?id={course_module}', '{ausleihverwaltung}');
        $rules[] = new restore_log_rule('ausleihverwaltung', 'update', 'view.php?id={course_module}', '{ausleihverwaltung}');
        $rules[] = new restore_log_rule('ausleihverwaltung', 'view', 'view.php?id={course_module}', '{ausleihverwaltung}');
=======
        $rules[] = new restore_log_rule('ausleihverwaltung', 'add', 'view.php?id={course_module}', '{ausleihverwaltung}');
        $rules[] = new restore_log_rule('ausleihverwaltung', 'update', 'view.php?id={course_module}', '{ausleihverwaltung}');
        $rules[] = new restore_log_rule('ausleihverwaltung', 'view', 'view.php?id={course_module}', '{ausleihverwaltung}');
>>>>>>> ausleihverwaltung:source/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php

        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * course logs. It must return one array
     * of {@link restore_log_rule} objects
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance (cmid = 0)
     */
    static public function define_restore_log_rules_for_course() {
        $rules = array();

<<<<<<< HEAD:ausleihverwaltung/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php
        $rules[] = new restore_log_rule('ausleihverwaltung', 'view all', 'index.php?id={course}', null);
=======
        $rules[] = new restore_log_rule('ausleihverwaltung', 'view all', 'index.php?id={course}', null);
>>>>>>> ausleihverwaltung:source/backup/moodle2/restore_ausleihverwaltung_activity_task.class.php

        return $rules;
    }
}