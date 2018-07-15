<?php
/**
 * User: Sven
 * Date: 29.06.18
 * Time: 13:58
 */

namespace mod_checkdeadline\task;
use \DateTime;

//require_once(dirname(dirname(dirname(dirname(dirname(__FILE__))))).'/lib/classes/date.php');
require_once(dirname(dirname(dirname(__FILE__))).'/locallib.php');



class checkDeadlineTask extends \core\task\scheduled_task {
    public function get_name() {
        // Shown in admin screens
        return 'Überprüfung auf resourcen in Verzug';
    }

    public function execute() {
        global $DB;
        global $CFG;

        // Alle Datensätze aus der DB-Tabelle >>$ausleihverwaltung_borroweddevice<< abfragen.
        $borrowed = $DB->get_records('checkdeadline_borroweddevice');
        $responsible = $DB->get_records('checkdeadline_responsible');

        //Aktuelle GMT Systemzeit im Epoch Time Format (Sekunden seit 1. Januar 1970). Deutschland ist GMT plus 2
        $now = new DateTime();
        $now = $now->getTimestamp();
        $yesterday = $now - 129600;

        foreach ($borrowed as $borrowed) {
            if ($borrowed->accepted && !($borrowed->returned)){
                $duedate = $borrowed->duedate;
                if ($duedate < $now && $duedate > $yesterday){
                    $resourceId = $borrowed->resourceid;
                    $studentmatrikelnummer = $borrowed->studentmatrikelnummer;
                    $studentmailaddress = $borrowed->studentmailaddress;
                    $studentName = $borrowed->studentname;

                    $mailText = "Sehr geehrter " . $studentName . ",
                    
sie haben ein Gerät der DHBW Mannheim mit der ID " . $resourceId . " in ihrem Besitzt. Wir mussten leider feststellen, dass die Rückgabefrist überschritten wurde. 
Wir möchten Sie daher bitten, die ausgeliehene resource schnellstmöglich an die verantwortliche Person zurückzugeben. 

Dies ist eine automatisch generierte Mail. Antworten auf diese Mail werden daher unbeantwortet bleiben.

Mit freundlichm Gruß, 
das IT Team der DHBW Mannheim";

                    mail_to($studentmailaddress, $studentName, 'Überfälliges Device', $mailText);

                    foreach ($responsible as $res) {
                        $responsibleMail = $res->dudesmail;
                        $responsibleName = $res->dudesname;
                        $mailText = "Sehr geehrter ". $responsibleName . ",
Das Gerät mit der Geräte ID " . $resourceId . ", welches vom Studenten " . $studentName . " mit der Matrikelnummer " . $studentmatrikelnummer . " und der E-Mail-Adresse " . $studentmailaddress . " ausgeliehen wurde ist überfällig.
Eine Erinnerung mit der Bitte zur schnellstmöglichen Rückgabe wurde verschickt. Falls Sie in den Nächsten Tagen keine Reaktion des Studierenden wahrnehmen, leiten Sie weitere Schritte gegen den Studierenden ein.

Dies ist eine automatisch generierte Mail. Antworten auf diese Mail werden daher unbeantwortet bleiben.

Mit freundlichm Gruß, 
das IT Team der DHBW Mannheim";

                        mail_to($responsibleMail, $responsibleName, 'Überfälliges Device', $mailText);
                    }
                }
            }
        }
    }

    public function get_run_if_component_disabled(){
        return true;
    }
}
