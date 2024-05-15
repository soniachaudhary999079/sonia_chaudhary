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
 * @package    local_sendrandommails
 * @copyright  2024 Send Random Email To Users
 * @author     Sonia Chaudhary (sonia999079@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Renderer for local_sendrandommails.
 */
class local_sendrandommails_renderer extends plugin_renderer_base {

    /**
     * Table to show CSV data before uploading
     * @param array CSV records of users
     * @return string User details table
     */
    public function preview_table($csvdata) {

        $columns = [get_string('srno', 'local_sendrandommails'),
        get_string('firstname', 'local_sendrandommails'),
        get_string('lastname', 'local_sendrandommails'),
        get_string('emailid', 'local_sendrandommails')];

        $table = new html_table();
        $table->head = $columns;
        $table->data = [];

        $i = 1;
        foreach ($csvdata as $record) {
            $row = [
                $i,
                $record['firstname'],
                $record['lastname'],
                $record['email'],
            ];
            $table->data[] = $row;
            $i++;
        }

        $output = html_writer::table($table);

        return $output;
    }

    /**
     * Table to show list of all uploaded users
     * @param object All uploaded users
     * @return string Users details table
     */
    public function show_user_list($userlist) {

        // If records are available then show table else show no records message.
        if ($userlist) {
            $table = new html_table();
            $table->head = [get_string('srno', 'local_sendrandommails'),
            get_string('firstname', 'local_sendrandommails'),
            get_string('lastname', 'local_sendrandommails'),
            get_string('emailid', 'local_sendrandommails'),
            get_string('emailsenttime', 'local_sendrandommails')];
            $i = 1;
            foreach ($userlist as $user) {
                $table->data[] = [
                    $i,
                    $user->firstname,
                    $user->lastname,
                    $user->email,
                    $user->mailsent == 0 ? "--" : date('h:i A d M, Y', $user->mailsent),
                ];
                $i++;
            }
            $output = html_writer::table($table);
        } else {
            $output = $this->output->notification(get_string('norecordmessage', 'local_sendrandommails'), 'notifymessage');
        }
        return $output;
    }

    // Function to add navigation links.
    /**
     * This function will display buttons for navigation.
     */
    public function local_navigation() {
        $o = '';
        $o .= html_writer::link(
            new moodle_url('/local/sendrandommails/index.php'),
            'Upload Users',
            ['class' => 'btn btn-primary mr-2']
        );
        $o .= html_writer::link(
            new moodle_url('/local/sendrandommails/usersdetails.php'),
            get_string('alluserslist', 'local_sendrandommails'),
            ['class' => 'btn btn-success mr-2']
        );
        return $o;
    }
}
