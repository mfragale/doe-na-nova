<?php
global $current_user;
global $stripe_is_TEST_mode;
global $stripe_is_LIVE_mode;
global $show_report;
global $doenanova_options;

$stripe = new \Stripe\StripeClient(
		$stripe_secret_key
	);

if ( get_user_meta( get_current_user_id(), '_stripe_customer_LIVE_id', true ) && isset($stripe_is_LIVE_mode)) {
	$customer_id = get_user_meta( get_current_user_id(), '_stripe_customer_LIVE_id', true );
} else if ( get_user_meta( get_current_user_id(), '_stripe_customer_TEST_id', true ) && isset($stripe_is_TEST_mode)) {
	$customer_id = get_user_meta( get_current_user_id(), '_stripe_customer_TEST_id', true );
} else {
	$customer_id = false;
}
