<?php

/**
 * Common code for Stripe PHP
 */

//to retrive the API keys from admin page
global $doenanova_options;

// // Send startup errors to stdout(1) vs stderr (0)
// ini_set('display_startup_errors', 1);
// // Send runtime errors to stdout(1) vs stderr (0)
// ini_set("display_errors", 1);
// // PHP levels of error reporting
// error_reporting(E_ALL);

// Stripe API keys
if (isset($doenanova_options['stripe_live_publishable_key']) && isset($doenanova_options['stripe_live_secret_key']) && isset($doenanova_options['enable_stripe_live_mode'])) {
	$stripe_publishable_key = $doenanova_options['stripe_live_publishable_key'];
	$stripe_secret_key = $doenanova_options['stripe_live_secret_key'];

	$stripe_is_LIVE_mode = true;
} else if (isset($doenanova_options['stripe_test_publishable_key']) && isset($doenanova_options['stripe_test_secret_key']) && !isset($doenanova_options['enable_stripe_live_mode'])) {
	$stripe_publishable_key = $doenanova_options['stripe_test_publishable_key'];
	$stripe_secret_key = $doenanova_options['stripe_test_secret_key'];

	$stripe_is_TEST_mode = true;
} else {
	$stripe_publishable_key = "xxxxxx";
	$stripe_secret_key = "xxxxxx";

	$stripe_is_TEST_mode = true;
}

//Show report
if (isset($doenanova_options['show_report'])) {
	$show_report = true;
} else {
	$show_report = false;
}

// File path to the Strip library
$path_to_stripe_lib = dirname(__FILE__) . '/vendor/stripe/stripe-php-7.97.0/';
