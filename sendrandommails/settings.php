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
 * Settings file for local_sendrandommails.
 *
 * @package    local_sendrandommails
 * @copyright  2024 Send Random Email To Users
 * @author     Sonia Chaudhary (sonia999079@gmail.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Link to our plugin local_sendrandommails.
    $ADMIN->add('localplugins', new admin_externalpage(
        'local_sendrandommails',
        get_string('pluginname', 'local_sendrandommails'),
        new moodle_url($CFG->wwwroot . "/local/sendrandommails")
    ));

    // Setting page to set random email.
    $settings = new admin_settingpage('sendrandommails',
    new lang_string('sendrandomemailtousers', 'local_sendrandommails'));

    $settings->add(
        new admin_setting_configtextarea(
            'local_sendrandommails/sendrandomemailtousers',
            new lang_string('sendrandomemailtousers', 'local_sendrandommails'),
            new lang_string('sendrandomemailtousers_desc', 'local_sendrandommails'),
            new lang_string('sendrandomemailtousers_mail', 'local_sendrandommails'),
            PARAM_RAW
        )
    );

    $ADMIN->add('localplugins', $settings);
}
