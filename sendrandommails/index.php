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

require_once("../../config.php");
require_login();
require_once($CFG->dirroot . "/local/sendrandommails/lib.php");
require_once($CFG->dirroot . "/local/sendrandommails/upload_users_form.php");
// CSV upload form.
$csvuploadform = new csv_upload_form();

// Setting page details.
$PAGE->set_url(new moodle_url("/local/sendrandommails/index.php"));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string("pluginname", "local_sendrandommails"));
$PAGE->set_heading(get_string('pluginname', 'local_sendrandommails'));

// Check if user is logged-in and has capability to Upload CSV.
if (!has_capability("local/sendrandommails:uploadcsv", context_system::instance())) {
    redirect(new moodle_url(('/my/')));
}
// Get renderer of local_sendrandommails.
$renderer = $PAGE->get_renderer('local_sendrandommails');

echo $OUTPUT->header();
// Button to go to list of all users page.
echo $renderer->local_navigation();
// Display upload form OR CSV data confirm table if CSV form is submitted.
if ($csvdata = $csvuploadform->get_file_content('csvofusers')) {
    // Process csv data into array.
    $csvirdata = process_csv($csvdata);
    $uploaded = upload_csv($csvirdata->cir_id);
    if ($uploaded) {
        echo $OUTPUT->notification('Data Uploaded Successfully', 'notifymessage');
        echo $renderer->preview_table($csvirdata->userrecords);
    }
} else {
    // Display CSV upload form.
    $csvuploadform->display();
}

echo $OUTPUT->footer();
