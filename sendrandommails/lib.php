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
 * Library file for functions of local_sendrandommails.
 *
 * @package    local_sendrandommails
 * @copyright  2024 Send Random Email To Users
 * @author     Sonia Chaudhary (sonia999079@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Get all records of sendrandommails_userdetails table
 * @return object all user records
 */
function get_user_list() {
    global $DB;
    $records = $DB->get_records("sendrandommails_userdetails", null, 'id DESC');
    return $records;
}

/**
 * Process CSV file data of users.
 * @param string CSV file data.
 * @return object Object of processed data of CSV file and current CIR id.
 */
function process_csv($csvdata) {

    global $CFG;
    require_once($CFG->libdir . "/csvlib.class.php");

    // Required columns of CSV.
    $requiredcolumns = ['firstname', 'lastname', 'email'];

    // New CIR instance.
    $id = csv_import_reader::get_new_iid('csvofusers');
    $cir = new csv_import_reader($id, 'csvofusers');

    $cir->load_csv_content($csvdata, 'utf-8', ',');

    // CSV load error handling.
    $csverror = $cir->get_error();
    if (!is_null($csverror)) {
        throw new \moodle_exception('csvloaderror', 'local_sendrandommails', '',
                get_string('csvloaderror', 'local_sendrandommails'));
    }

    // CSV wrong columns error handling.
    if ($cir->get_columns() !== $requiredcolumns) {
        throw new \moodle_exception(
            'csvcolumnerror',
            'local_sendrandommails',
            '',
            get_string('csvcolumnerror', 'local_sendrandommails')
        );
    }

    $cir->init();

    $csvrecords = [];

    while ($cirrecord = $cir->next()) {
        $data = [];
        $data['firstname'] = $cirrecord[0];
        $data['lastname'] = $cirrecord[1];
        $data['email'] = $cirrecord[2];
        $csvrecords[] = $data;
    }

    $output = new stdClass();
    $output->userrecords = $csvrecords;
    $output->cir_id = $id;

    return $output;
}

/**
 * Upload records into sendrandommails_userdetails table
 * @param int $cirid id of csv import reader
 * @return bool true when records are uploaded
 */
function upload_csv($cirid) {
    global $DB, $CFG;
    require_once($CFG->libdir . "/csvlib.class.php");

    // Using our CIR id again to initialize csv import reader.
    $cir = new csv_import_reader($cirid, 'csvofusers');
    $cir->init();

    // Insert csv records one by one.
    while ($cirrecord = $cir->next()) {
        $record = new stdClass();
        $record->firstname = $cirrecord[0];
        $record->lastname = $cirrecord[1];
        $record->email = $cirrecord[2];
        $record->timecreated = time();
        $record->id = $DB->insert_record('sendrandommails_userdetails', $record);
    }

    return true;
}

/**
 * Generate link to example.csv
 * @return link to example.csv file.
 */
function sample_csv() {
    $url = new moodle_url("sample.csv");
    $link = html_writer::link($url, get_string('examplecsv', 'local_sendrandommails'));

    return $link;
}
