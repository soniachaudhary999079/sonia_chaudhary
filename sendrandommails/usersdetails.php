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
 * List of all the uploaded users of local_sendrandommails.
 *
 * @package    local_sendrandommails
 * @copyright  2024 Send Random Email To Users
 * @author     Sonia Chaudhary (sonia999079@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../config.php");
require_once($CFG->dirroot . "/local/sendrandommails/lib.php");

// Setting page details.
$PAGE->set_url(new moodle_url("/local/sendrandommails/usersdetails.php"));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string("alluserslist", "local_sendrandommails"));
$PAGE->set_heading(get_string('pluginname', 'local_sendrandommails'));

// Checking if the logged in user has the capability to Upload CSV.
require_login();
if (!has_capability("local/sendrandommails:uploadcsv", context_system::instance())) {
    redirect(new moodle_url(('/my/')));
}

// Calling renderer of local_sendrandommails.
$renderer = $PAGE->get_renderer('local_sendrandommails');

// Get all records of sendrandommails_userdetails table.
$usersdetails = get_user_list();

echo $OUTPUT->header();

// Navigation.
echo $renderer->local_navigation();

// Rendering user table.
echo $renderer->show_user_list($usersdetails);

echo $OUTPUT->footer();
