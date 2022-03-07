# Stripe Availability Condition for Moodle
----------------------------------------------

Moodle Availability Stripe is a Moodle Availability Condition plugin, with this plugins, you can put a price in any course content and ask for a Stripe payment to allow access.

The person in charge has to configure the enrolment method on the course. For accessing resource or activity a charge or cost can be associated.

It works with "course modules and resources".


## Features
- Easy Integration
- Personalised payment experience
- Multiple Currency Support
- third party transaction history with dashboard


## Configuration

You can install this plugin from [Moodle plugins directory](https://moodle.org/plugins) or can download from [Github](https://github.com/eLearning-BS23/moodle-availability_stripe).

You can download zip file and install or you can put file under availability/condition as Stripe

## Plugin Global Settings
### Go to
```
  Dashboard->Site administration->Plugins->Availability restrictions
->Stripe settings
```

<img src="https://i.imgur.com/Y9RJgWe.png">


##How to create Stripe account
-Create account at https://stripe.com.
-Complete your merchant profile details from https://dashboard.stripe.com/account.
-Now set up secret key and publishers key at https://dashboard.stripe.com/account/apikeys.
-For test mode use test api keys and for live mode use live api keys.
-Now you are done with merchant account set up.

## Usage

<img src="https://i.imgur.com/O88VjuW.png">

This works like the [Stripe enrol plugin](https://moodle.org/plugins/enrol_Stripe), but instead of restricting the full course, you can restrict individual activities, resources or sections (and you can combine it with other availability conditions, for example, to exclude some group from paying using an "or" restriction set).

For each restriction you add, you can set a business email address, cost, currency.

## License

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see http://www.gnu.org/licenses/.

