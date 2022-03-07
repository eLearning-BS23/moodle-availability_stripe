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
 * Prints a particular instance of stripe
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    availability_stripe
 * @copyright  2021 Brain station 23 ltd <https://brainstation-23.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');
require('config.php');
require_once($CFG->dirroot . '/availability/condition/stripe/lib.php');

global $DB;

$cmid = optional_param('cmid', 0, PARAM_INT);
$sectionid = optional_param('sectionid', 0, PARAM_INT);

if (!$cmid && !$sectionid) {
    print_error('invalidparam');
}

if ($cmid) {
    $availability = $DB->get_record('course_modules', ['id' => $cmid], 'course, availability', MUST_EXIST);
    $contextid = $DB->get_field('context', 'id', ['contextlevel' => CONTEXT_MODULE, 'instanceid' => $cmid]);
    $urlparams = ['cmid' => $cmid];
} else {
    $availability = $DB->get_record('course_sections', ['id' => $sectionid], 'course, availability', MUST_EXIST);
    $contextid = $DB->get_field('context', 'id', ['contextlevel' => CONTEXT_COURSE, 'instanceid' => $availability->course]);
    $urlparams = ['sectionid' => $sectionid];
}

$conditions = json_decode($availability->availability);
$stripe = availability_stripe_find_condition($conditions);

if (is_null($stripe)) {
    print_error('no stripe condition for this context.');
}

$course = $DB->get_record('course', ['id' => $availability->course]);
$context = \context::instance_by_id($contextid);

require_login($course);

if (isset($_POST)) {
    // Calculate localised and "." cost, make sure we send PayPal the same value,
    // please note PayPal expects amount with 2 decimal places and "." separator.
    $localisedcost = format_float($stripe->cost, 2, true);
    $cost = format_float($stripe->cost, 2, false) * 100;
    if (isset($_POST['stripeToken']) && !empty($_POST['stripeToken'])) {

        if ($cost == $_POST['amount']) {
            Stripe\Stripe::setApiKey($secretkey);

            $stripe = \Stripe\Charge::create([
                "amount" => intval($_POST['amount']),
                "currency" => $_POST['currency_code'],
                "description" => '',
                "source" => $_POST['stripeToken']
            ]);

            $req = 'cmd=_notify-validate';

            foreach ($_POST as $key => $value) {
                $req .= "&$key=" . urlencode($value);
            }

            $data = new stdclass();
            $data->business = optional_param('business', '', PARAM_TEXT);
            $data->receiver_email = optional_param('receiver_email', '', PARAM_TEXT);
            $data->receiver_id = optional_param('receiver_id', '', PARAM_TEXT);
            $data->item_name = optional_param('item_name', '', PARAM_TEXT);
            $data->memo = (string)$stripe->source->id;
            $data->tax = optional_param('tax', '', PARAM_TEXT);
            $data->option_name1 = optional_param('option_name1', '', PARAM_TEXT);
            $data->option_selection1_x = optional_param('option_selection1_x', '', PARAM_TEXT);
            $data->option_name2 = optional_param('option_name2', '', PARAM_TEXT);
            $data->option_selection2_x = optional_param('option_selection2_x', '', PARAM_TEXT);
            $data->payment_status = optional_param('payment_status', '', PARAM_TEXT);
            $data->pending_reason = optional_param('pending_reason', '', PARAM_TEXT);
            $data->reason_code = optional_param('reason_code', '', PARAM_TEXT);
            $data->txn_id = optional_param('stripeToken', '', PARAM_TEXT);
            $data->parent_txn_id = optional_param('parent_txn_id', '', PARAM_TEXT);
            $data->payment_type = optional_param('stripeTokenType', '', PARAM_TEXT);
            $data->payment_gross = optional_param('mc_gross', '', PARAM_TEXT);
            $data->payment_currency = optional_param('currency_code', '', PARAM_TEXT);

            $custom = optional_param('custom', '', PARAM_TEXT);
            $custom = explode('-', $custom);

            $data->userid = (int)($custom[0] ?? -1);
            $data->contextid = (int)($custom[1] ?? -1);
            $data->sectionid = (int)($custom[2] ?? -1);
            $data->payment_status = (string)('Completed');

            $data->timeupdated = time();

            $DB->insert_record('availability_stripe_tnx', $data);
        } else {
            redirect($context->get_url(), get_string('stripecheat', 'availability_stripe'));
        }
    }
}

$tnxparams = ['userid' => $USER->id, 'contextid' => $contextid, 'sectionid' => $sectionid];
$paymenttnx = $DB->get_record('availability_stripe_tnx', $tnxparams + ['payment_status' => 'Completed']);
if ($paymenttnx) {
    redirect($context->get_url(), get_string('paymentcompleted', 'availability_stripe'));
}
$paymenttnx = $DB->get_record('availability_stripe_tnx', $tnxparams);

$PAGE->set_url('/availability/condition/stripe/view.php', $urlparams);
$PAGE->set_title($course->fullname);
$PAGE->set_heading($course->fullname);

$PAGE->navbar->add($course->fullname);

echo $OUTPUT->header(),
$OUTPUT->heading($course->fullname);

if ($paymenttnx && ($paymenttnx->payment_status == 'Pending')) {
    echo get_string('paymentpending', 'availability_stripe');
} else {

    // Calculate localised and "." cost, make sure we send PayPal the same value,
    // please note PayPal expects amount with 2 decimal places and "." separator.
    $localisedcost = format_float($stripe->cost, 2, true);
    $cost = format_float($stripe->cost, 2, false) * 100;

    if (isguestuser()) { // Force login only for guest user, not real users with guest role.
        if (empty($CFG->loginhttps)) {
            $wwwroot = $CFG->wwwroot;
        } else {
            // This actually is not so secure ;-), 'cause we're in unencrypted connection...
            $wwwroot = str_replace("http://", "https://", $CFG->wwwroot);
        }
        echo '<div class="mdl-align"><p>' . get_string('paymentrequired', 'availability_stripe') . '</p>';
        echo '<div class="mdl-align"><p>' . get_string('paymentwaitremider', 'availability_stripe') . '</p>';
        echo '<p><b>' . get_string('cost') . ": $instance->currency $localisedcost" . '</b></p>';
        echo '<p><a href="' . $wwwroot . '/login/">' . get_string('loginsite') . '</a></p>';
        echo '</div>';
    } else {
        // Sanitise some fields before building the PayPal form.
        $userfullname = fullname($USER);
        $userfirstname = $USER->firstname;
        $userlastname = $USER->lastname;
        $useraddress = $USER->address;
        $usercity = $USER->city;
        ?>
        <p><?php print_string("paymentrequired", 'availability_stripe') ?></p>
        <p><b><?php echo get_string("cost") . ": {$stripe->currency} {$localisedcost}"; ?></b></p>
        <p>
            <svg viewBox="0 0 60 25" xmlns="http://www.w3.org/2000/svg" width="60" height="25"
                 class="UserLogo variant-- "><title>Stripe logo</title>
                <path fill="var(--userLogoColor, #0A2540)"
                      d="M59.64 14.28h-8.06c.19 1.93 1.6 2.55 3.2 2.55 1.64 0 2.96-.37 4.05-.95v3.32a8.33 8.33
                      0 0 1-4.56 1.1c-4.01 0-6.83-2.5-6.83-7.48 0-4.19 2.39-7.52 6.3-7.52 3.92 0 5.96 3.28 5.96
                      7.5 0 .4-.04 1.26-.06 1.48zm-5.92-5.62c-1.03 0-2.17.73-2.17 2.58h4.25c0-1.85-1.07-2.58-2.08-2.58zM40.95
                      20.3c-1.44 0-2.32-.6-2.9-1.04l-.02 4.63-4.12.87V5.57h3.76l.08 1.02a4.7 4.7 0 0 1
                      3.23-1.29c2.9 0 5.62 2.6 5.62 7.4 0 5.23-2.7 7.6-5.65 7.6zM40 8.95c-.95 0-1.54.34-1.97.81l.02
                      6.12c.4.44.98.78 1.95.78 1.52 0 2.54-1.65 2.54-3.87 0-2.15-1.04-3.84-2.54-3.84zM28.24
                      5.57h4.13v14.44h-4.13V5.57zm0-4.7L32.37 0v3.36l-4.13.88V.88zm-4.32
                      9.35v9.79H19.8V5.57h3.7l.12 1.22c1-1.77 3.07-1.41
                      3.62-1.22v3.79c-.52-.17-2.29-.43-3.32.86zm-8.55 4.72c0 2.43 2.6 1.68 3.12
                      1.46v3.36c-.55.3-1.54.54-2.89.54a4.15
                       4.15 0 0 1-4.27-4.24l.01-13.17 4.02-.86v3.54h3.14V9.1h-3.13v5.85zm-4.91.7c0
                       2.97-2.31 4.66-5.73 4.66a11.2 11.2 0 0 1-4.46-.93v-3.93c1.38.75 3.1 1.31 4.46
                       1.31.92 0 1.53-.24 1.53-1C6.26 13.77 0 14.51 0 9.95 0 7.04 2.28 5.3 5.62 5.3c1.36
                       0 2.72.2 4.09.75v3.88a9.23 9.23 0 0 0-4.1-1.06c-.86 0-1.44.25-1.44.9 0 1.85 6.29.97 6.29 5.88z"
                      fill-rule="evenodd"></path>
            </svg>
        </p>
        <p><?php print_string("paymentinstant", 'availability_stripe') ?></p>

        <form action="" method="post">
            <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                    data-key="<?php echo $publishablekey; ?>"
                    data-amount="<?php echo $cost; ?>"
                    data-name="<?php echo $course->fullname; ?>"
            data-image=""
            data-currency="<?php p($stripe->currency) ?>"
            >
            </script>
            <input type="hidden" name="cmd" value="_xclick"/>
            <input type="hidden" name="charset" value="utf-8"/>
            <input type="hidden" name="business" value="<?php p($stripe->businessemail) ?>"/>
            <input type="hidden" name="item_name" value="<?php p($course->fullname) ?>"/>
            <input type="hidden" name="item_number" value="<?php p($stripe->itemnumber) ?>"/>
            <input type="hidden" name="quantity" value="1"/>
            <input type="hidden" name="on0" value="<?php print_string("user") ?>"/>
            <input type="hidden" name="os0" value="<?php p($userfullname) ?>"/>
            <input type="hidden" name="custom" value="<?php echo "{$USER->id}-{$contextid}-{$sectionid}" ?>"/>

            <input type="hidden" name="currency_code" value="<?php p($stripe->currency) ?>"/>
            <input type="hidden" name="amount" value="<?php p($cost) ?>"/>

            <input type="hidden" name="for_auction" value="false"/>
            <input type="hidden" name="no_note" value="1"/>
            <input type="hidden" name="no_shipping" value="1"/>
            <input type="hidden" name="notify_url"
                   value="<?php echo "{$CFG->wwwroot}/availability/condition/stripe/ipn.php" ?>"/>
            <input type="hidden" name="return" value="<?php echo $PAGE->url->out(false); ?>"/>
            <input type="hidden" name="cancel_return" value="<?php echo $PAGE->url->out(false); ?>"/>
            <input type="hidden" name="rm" value="2"/>
            <input type="hidden" name="cbt" value="<?php print_string("continue", 'availability_stripe') ?>"/>

            <input type="hidden" name="first_name" value="<?php p($userfirstname) ?>"/>
            <input type="hidden" name="last_name" value="<?php p($userlastname) ?>"/>
            <input type="hidden" name="address" value="<?php p($useraddress) ?>"/>
            <input type="hidden" name="city" value="<?php p($usercity) ?>"/>
            <input type="hidden" name="email" value="<?php p($USER->email) ?>"/>
            <input type="hidden" name="country" value="<?php p($USER->country) ?>"/>

        </form>
        <?php
    }
}
echo $OUTPUT->footer();
