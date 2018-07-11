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
<<<<<<< HEAD:ausleihverwaltung/db/access.php
 * Capability definitions for the ausleihverwaltung module
=======
 * Capability definitions for the ausleihverwaltung module
>>>>>>> ausleihverwaltung:source/db/access.php
 *
 * The capabilities are loaded into the database table when the module is
 * installed or updated. Whenever the capability definitions are updated,
 * the module version number should be bumped up.
 *
 * The system has four possible values for a capability:
 * CAP_ALLOW, CAP_PREVENT, CAP_PROHIBIT, and inherit (not set).
 *
 * It is important that capability names are unique. The naming convention
 * for capabilities that are specific to modules and blocks is as follows:
 *   [mod/block]/<plugin_name>:<capabilityname>
 *
 * component_name should be the same as the directory name of the mod or block.
 *
 * Core moodle capabilities are defined thus:
 *    moodle/<capabilityclass>:<capabilityname>
 *
 * Examples: mod/forum:viewpost
 *           block/recent_activity:view
 *           moodle/site:deleteuser
 *
 * The variable name for the capability definitions array is $capabilities
 *
<<<<<<< HEAD:ausleihverwaltung/db/access.php
 * @package    mod_ausleihverwaltung
=======
 * @package    mod_ausleihverwaltung
>>>>>>> ausleihverwaltung:source/db/access.php
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Modify capabilities as needed and remove this comment.
$capabilities = array(
<<<<<<< HEAD:ausleihverwaltung/db/access.php
    'mod/ausleihverwaltung:addinstance' => array(
=======
    'mod/ausleihverwaltung:addinstance' => array(
>>>>>>> ausleihverwaltung:source/db/access.php
        'riskbitmask' => RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),
        'clonepermissionsfrom' => 'moodle/course:manageactivities'
    ),

<<<<<<< HEAD:ausleihverwaltung/db/access.php
    'mod/ausleihverwaltung:view' => array(
=======
    'mod/ausleihverwaltung:view' => array(
>>>>>>> ausleihverwaltung:source/db/access.php
        'captype' => 'read',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'guest' => CAP_ALLOW,
            'user' => CAP_ALLOW,
        )
    ),

<<<<<<< HEAD:ausleihverwaltung/db/access.php
    'mod/ausleihverwaltung:submit' => array(
=======
    'mod/ausleihverwaltung:submit' => array(
>>>>>>> ausleihverwaltung:source/db/access.php
        'riskbitmask' => RISK_SPAM,
        'captype' => 'write',
        'contextlevel' => CONTEXT_MODULE,
        'archetypes' => array(
            'student' => CAP_ALLOW
        )
    ),
);
