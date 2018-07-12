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
 * Defines the view event.
 *
<<<<<<< HEAD:ausleihverwaltung/classes/event/course_module_viewed.php
 * @package    mod_ausleihverwaltung
=======
 * @package    mod_ausleihverwaltung
>>>>>>> ausleihverwaltung:source/classes/event/course_module_viewed.php
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

<<<<<<< HEAD:ausleihverwaltung/classes/event/course_module_viewed.php
namespace mod_ausleihverwaltung\event;
=======
namespace mod_ausleihverwaltung\event;
>>>>>>> ausleihverwaltung:source/classes/event/course_module_viewed.php

defined('MOODLE_INTERNAL') || die();

/**
<<<<<<< HEAD:ausleihverwaltung/classes/event/course_module_viewed.php
 * The mod_ausleihverwaltung instance viewed event class
=======
 * The mod_ausleihverwaltung instance viewed event class
>>>>>>> ausleihverwaltung:source/classes/event/course_module_viewed.php
 *
 * If the view mode needs to be stored as well, you may need to
 * override methods get_url() and get_legacy_log_data(), too.
 *
<<<<<<< HEAD:ausleihverwaltung/classes/event/course_module_viewed.php
 * @package    mod_ausleihverwaltung
=======
 * @package    mod_ausleihverwaltung
>>>>>>> ausleihverwaltung:source/classes/event/course_module_viewed.php
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course_module_viewed extends \core\event\course_module_viewed {

    /**
     * Initialize the event
     */
    protected function init() {
<<<<<<< HEAD:ausleihverwaltung/classes/event/course_module_viewed.php
        $this->data['objecttable'] = 'ausleihverwaltung';
=======
        $this->data['objecttable'] = 'ausleihverwaltung';
>>>>>>> ausleihverwaltung:source/classes/event/course_module_viewed.php
        parent::init();
    }
}
