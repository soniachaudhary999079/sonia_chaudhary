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
 * Form to upload CSV of users.
 *
 * @package    local_sendrandommails
 * @copyright  2024 Send Random Email To Users
 * @author     Sonia Chaudhary (sonia999079@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . "/formslib.php");

// Form for uploading CSV of users.
class csv_upload_form extends moodleform {
    /**
     * Defines the form.
     */
    // Definition function.
    public function definition() {

        $mform = $this->_form;

        $link = sample_csv();
        $mform->addElement('static', 'examplecsv', get_string('examplecsv', 'local_sendrandommails'), $link);
        $mform->addHelpButton('examplecsv', 'examplecsv', 'tool_uploaduser');

        // File picker element to upload CSV file.
        $mform->addElement(
            'filepicker',
            'csvofusers',
            get_string('addcsvfile', 'local_sendrandommails'),
            null,
            ['accepted_types' => ['.csv']]
        );
        $mform->setType('csvofusers', PARAM_FILE);
        $mform->addRule('csvofusers', null, 'required', null, 'client');

        $this->add_action_buttons();
    }
}
