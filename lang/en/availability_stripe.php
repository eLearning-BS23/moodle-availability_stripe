<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Language strings.
 *
 * @package     availability_stripe
 * @category    string
 * @copyright   2021 Brain station 23 ltd <https://brainstation-23.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['ajaxerror'] = 'Error contacting server';
$string['pluginname_desc'] = 'Stripe Availability Payment';
$string['publickey'] = 'Public Key';
$string['publickey_desc'] = 'Stripe Public key';
$string['secrectkey'] = 'Secrect Key';
$string['secrectkey_desc'] = 'Stripe Secrect key';
$string['businessemail'] = 'Business email';
$string['continue'] = 'Click here and go back to Moodle';
$string['cost'] = 'Cost';
$string['currency'] = 'Currency';
$string['description'] = 'Require users to make a payment via Stripe to access the activity or resource.';
$string['eitherdescription'] = 'you make a <a href="{$a}">payment with Stripe</a>';
$string['error_businessemail'] = 'You must provide a business email.';
$string['error_cost'] = 'You must provide a cost and it must be greater than 0.';
$string['error_itemname'] = 'You must provide an item name.';
$string['error_itemnumber'] = 'You must provide an item number.';
$string['itemname'] = 'Item name';
$string['itemname_help'] = 'Name of the item to be shown on Stripe form';
$string['itemnumber'] = 'Item number';
$string['messageprovider:payment_error'] = 'Payment errors';
$string['messageprovider:payment_pending'] = 'Pending payments';
$string['notdescription'] = 'you have not sent a <a href="{$a}">payment with Stripe</a>';
$string['paymentcompleted'] = 'Your payment was accepted and now you can access the activity or resource. Thank you.';
$string['paymentinstant'] = 'Use the button below to pay and access the activity or resource.';
$string['paymentpending'] = 'There is a pending payment registered for you.';
$string['paymentrequired'] = 'You must make a payment via Stripe to access the activity or resource.';
$string['paymentwaitreminder'] = 'Please note that if you already made a payment recently, it should be processing. Please wait a few minutes and refresh this page.';
$string['stripe:receivenotifications'] = 'Receive payment notifications';
$string['stripeaccepted'] = 'Stripe payments accepted';
$string['stripecheat'] = 'You cheated or something wrong';
$string['pluginname'] = 'Stripe';
$string['sendpaymentbutton'] = 'Send payment via Stripe';
$string['title'] = 'Stripe payment';
