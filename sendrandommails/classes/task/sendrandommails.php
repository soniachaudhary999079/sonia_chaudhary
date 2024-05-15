<?php // This file is part of Moodle - http://moodle.org/
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
 * @package    local_sendrandommails
 * @copyright  2024 Send Random Email To Users
 * @author     Sonia Chaudhary (sonia999079@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_sendrandommails\task;

use stdClass;

/**
 * Scheduled task to send email to users of local_sendrandommails.
 */
class sendrandommails extends \core\task\scheduled_task {

    /**
     * get_name
     *
     * @return string
     */
    public function get_name() {
        return get_string('pluginname', 'local_sendrandommails');
    }

    /**
     * execute
     */
    public function execute() {
        global $USER, $DB;

        mtrace("Sending queued mail to user of local_sendrandommails plugin.");

        // From user.
        $fromuser = $USER;
        $data = $DB->get_records('sendrandommails_userdetails', ['mailsent' => 0]);

        foreach ($data as $user) {
            // To user.
            $touser = clone $USER;
            $touser->email = $user->email;
            $touser->firstname = $user->firstname;
            $touser->lastname = $user->lastname;

            // Subject of the email.
            $subject = get_string('sendrandomemailtousers_subject', 'local_sendrandommails');

            // Body of the email.
            $body = get_config('local_sendrandommails', 'sendrandomemailtousers');

            // Send mail.
            $mailsent = email_to_user($touser, $fromuser, $subject, '', $body);

            // If mail is sent update mail sent timings in the sendrandommails_usersetails table.
            if ($mailsent) {
                $userdetail = new stdClass();
                $userdetail->id = $user->id;
                $userdetail->mailsent = time();
                $DB->update_record('sendrandommails_userdetails', $userdetail);
            }
        }
    }
}
