<?php
/**
 * User: Sven
 * Date: 29.06.18
 * Time: 13:58
 */

namespace mod_ausleihverwaltung\task;
use \DateTime;

//require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/lib/classes/date.php');
require_once(dirname(dirname(dirname(__FILE__))).'/locallib.php');



class checkDeadlineTask extends \core\task\scheduled_task {
    public function get_name() {
        // Shown in admin screens
        return 'ausleihverwaltung';
    }

    public function execute() {
        global $DB;
        global $CFG;
        global $responsibleMail;
        $supportUser = $DB->get_record('user', array('username' => 'supportuser')); //the message is sent from a bot user "Support User"
        $receivingUser = $DB->get_record('user', array('username' => 'user')); //the message is sent from a bot user "Support User"
        $course = $DB->get_record('course', array('shortname' => 'testcourse')); //the message is sent from a bot user "Support User"
        //message creation
        $message = new \core\message\message();
        $message->component = 'moodle';
        $message->name = 'instantmessage';
        $message->userfrom = $supportUser;
        $message->userto = $receivingUser;
        $message->subject = 'Check Deadline';
        $message->fullmessageformat = FORMAT_MARKDOWN;
        $message->fullmessagehtml = '<p>message body</p>';
        $message->notification = '0';
        $message->contexturl = '';
        $message->contexturlname = 'Context name';
        $message->replyto = "";
        $content = array('*' => array('header' => ' test ', 'footer' => ' test ')); // Extra content for specific processor
        $message->set_additional_content('email', $content);
        $message->courseid = $course->id; // This is required in recent versions, use it from 3.2 on https://tracker.moodle.org/browse/MDL-47162
        /*$message->fullmessage = 'ausleihverwaltung task works';
        $message->smallmessage = 'ausleihverwaltung task works';             
        $messageid = message_send($message);*/

        //$coreDateObject = new \core\core_date\core_date();

        // Alle Datensätze aus der DB-Tabelle >>$ausleihverwaltung_borroweddevice<< abfragen.
        $borrowed = $DB->get_records('ausleihverwaltung_borroweddevice');
        //Aktuelle GMT Systemzeit im Epoch Time Format (Sekunden seit 1. Januar 1970) Deutschland ist GMT plus 2
        //$now = new DateTime("now", $coreDateObject);
        /*
         * Normalerweise sollte die Systemzeit mit
         */
        $now = new DateTime();
        $now = $now->getTimestamp();
        $yesterday = $now - 129600;

        foreach ($borrowed as $borrowed) {
            $duedate = $borrowed->duedate;
            $inventorynumber = $borrowed->inventorynumber;
            $studentmatrikelnummer = $borrowed->studentmatrikelnummer;
            $studentmailaddress = $borrowed->studentmailaddress;
            if ($duedate < $now ){ //&& $duedate > $yesterday
                $mailText = "Das Gerät mit der Inventarnummer " . $inventorynumber . ", welches vom 
                Studenten mit der Matrikelnummer " . $studentmatrikelnummer . " und der E-Mail-Adresse " .
                    $studentmailaddress . " ausgeliehen wurde ist überfällig.";
                mail_to('chs.padutsch@gmail.com', 'Sven', 'Überfälliges Device', $mailText);
            }
        }
    }

    public function get_run_if_component_disabled(){
        return true;
    }
}
