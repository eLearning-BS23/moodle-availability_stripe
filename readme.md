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

##How to create Stripe account
-Create account at https://stripe.com.
-Complete your merchant profile details from https://dashboard.stripe.com/account.
-Now set up secret key and publishers key at https://dashboard.stripe.com/account/apikeys.
-For test mode use test api keys and for live mode use live api keys.
-Now you are done with merchant account set up.

## Configuration

You can install this plugin from [Moodle plugins directory](https://moodle.org/plugins) or can download from [Github](https://github.com/eLearning-BS23/moodle-availability_sslcommerz).

You can download zip file and install or you can put file under enrol as sslcommerz

## Plugin Global Settings
### Go to
```
  Dashboard->Site administration->Plugins->Availability restrictions
->sslcommerz settings
```

<img src="https://i.imgur.com/tlj8TH5.png">




## Usage

<img src="https://i.imgur.com/IEqpHAL.png">
This works like the [sslcommerz enrol plugin](https://moodle.org/plugins/enrol_sslcommerz), but instead of restricting the full course, you can restrict individual activities, resources or sections (and you can combine it with other availability conditions, for example, to exclude some group from paying using an "or" restriction set).

For each restriction you add, you can set a business email address, cost, currency, item name and item number.

