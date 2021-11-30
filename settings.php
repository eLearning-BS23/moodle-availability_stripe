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
 * stripe enrolments plugin settings and presets.
 *
 * @package    availability_stripe
 * @copyright  2021 Brain station 23 ltd.
 * @author     Brain station 23 ltd.
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {

    // Settings.
    $settings->add(new admin_setting_heading('availability_stripe_settings', '',
        get_string('pluginname_desc', 'availability_stripe')));

    $settings->add(new admin_setting_configtext('availability_stripe/publickey',
        get_string('publickey', 'availability_stripe'),
        get_string('publickey_desc', 'availability_stripe'), '', PARAM_TEXT));

    $settings->add(new admin_setting_configtext('availability_stripe/secrectkey',
        get_string('secrectkey', 'availability_stripe'),
        get_string('secrectkey_desc', 'availability_stripe'), '', PARAM_TEXT));


}
