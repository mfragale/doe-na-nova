<?php

/**
 * Register Javascript scripts and localize variables.
 * @since 1.0.0
 */
function doe_na_nova_form_js()
{
	wp_register_script(
		'checkout-js',
		plugin_dir_url(__FILE__) . 'js/dist/checkout_ui-min.js',
		array('jquery'),
		'1.1',
		true
	);

	wp_register_script(
		'add-card-js',
		plugin_dir_url(__FILE__) . 'js/dist/add_card-min.js',
		array('jquery'),
		'1.1',
		true
	);

	wp_register_script(
		'functions-js',
		plugin_dir_url(__FILE__) . 'js/dist/functions-min.js',
		array('jquery', 'wp-i18n'),
		'1.1',
		true
	);

	wp_register_script(
		'ajax_load_more_recurring_donations-js',
		plugin_dir_url(__FILE__) . 'js/dist/ajax_load_more_recurring_donations-min.js',
		array('jquery', 'wp-i18n'),
		'1.1',
		true
	);

	wp_register_script(
		'ajax_load_more_charges-js',
		plugin_dir_url(__FILE__) . 'js/dist/ajax_load_more_charges-min.js',
		array('jquery', 'wp-i18n'),
		'1.1',
		true
	);

	wp_register_script(
		'ajax_load_more_cards-js',
		plugin_dir_url(__FILE__) . 'js/dist/ajax_load_more_cards-min.js',
		array('jquery', 'wp-i18n'),
		'1.1',
		true
	);

	global $stripe_publishable_key;

	$js_vars = array(
		'checkout-url' => admin_url('admin-ajax.php'),
		'action_url' => admin_url('admin-post.php'),
		'current_url' => get_permalink(),
		'stripe-pk' => $stripe_publishable_key,
		'wp-action-doenanova-load-more-charges' => 'doenanova_load_more_charges',
		'wp-action-doenanova-load-more-recurring-donations' => 'doenanova_load_more_recurring_donations',
		'wp-action-doenanova-load-more-cards' => 'doenanova_load_more_cards',
		'doe_na_nova_currency_symbol_js' => doe_na_nova_currency_symbol(),

		//Localization with JavaScript in WordPress - https://wpengineer.com/2181/localization-with-javascript-in-wordpress/
		'delete_this_recurring_donation' => __('Delete this recurring donation', 'doenanova'),
		'jan' => __('Jan', 'doenanova'),
		'feb' => __('Feb', 'doenanova'),
		'mar' => __('Mar', 'doenanova'),
		'apr' => __('Apr', 'doenanova'),
		'may' => __('May', 'doenanova'),
		'jun' => __('Jun', 'doenanova'),
		'jul' => __('Jul', 'doenanova'),
		'aug' => __('Aug', 'doenanova'),
		'sep' => __('Sep', 'doenanova'),
		'oct' => __('Oct', 'doenanova'),
		'nov' => __('Nov', 'doenanova'),
		'dec' => __('Dec', 'doenanova'),
		'mon' => __('Mon', 'doenanova'),
		'tue' => __('Tue', 'doenanova'),
		'wed' => __('Wed', 'doenanova'),
		'thu' => __('Thu', 'doenanova'),
		'fri' => __('Fri', 'doenanova'),
		'sat' => __('Sat', 'doenanova'),
		'sun' => __('Sun', 'doenanova'),
		'one_time' => __('One time', 'doenanova'),
		'week' => __('Weekly', 'doenanova'),
		'month' => __('Monthly', 'doenanova'),
		'year' => __('Yearly', 'doenanova'),
		'created_on' => __('Created on', 'doenanova'),
		'frequency' => __('Frequency', 'doenanova'),
		'next_billing' => __('Next billing', 'doenanova'),
		'amount' => __('Amount', 'doenanova'),
		'payment_method' => __('Payment method', 'doenanova'),
		'purpose' => __('Purpose', 'doenanova'),
		'status' => __('Status', 'doenanova'),
		'active' => __('Active', 'doenanova'),
	);

	wp_localize_script('functions-js', 'phpVars', $js_vars);
	wp_localize_script('checkout-js', 'phpVars', $js_vars);
	wp_localize_script('addcard-js', 'phpVars', $js_vars);
	wp_localize_script('ajax_load_more_charges-js', 'phpVars', $js_vars);
	// wp_localize_script('ajax_load_more_recurring_donations-js', 'phpVars', $js_vars);
	// wp_localize_script('ajax_load_more_cards-js', 'phpVars', $js_vars);
}
