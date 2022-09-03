<?php

/**
 * Plugin init hook
 */
add_action('init', 'doe_na_nova_form_init', 10);


/**
 * Admin page
 */
add_action('admin_menu', 'doenanova_add_options_link');
add_action('admin_init', 'doenanova_register_settings');


/**
 * Add wp_enqueue_scripts hook for Javascript files
 */
add_action('wp_enqueue_scripts', 'doe_na_nova_form_js');
/**
 * Add wp_enqueue_scripts hook for CSS files
 */
add_action('wp_enqueue_scripts', 'doe_na_nova_form_css');



/**
 * Registers doe-na-nova-nav
 */
add_action('init', 'register_doenanova_menu');



/**
 * FORMS action callback hooks
 */
add_action('admin_post_stripe_checkout', 'stripe_checkout'); // If the user is logged in
add_action('admin_post_nopriv_stripe_checkout', 'stripe_checkout'); // If the user in not logged in

add_action('admin_post_stripe_add_card', 'stripe_add_card'); // If the user is logged in
add_action('admin_post_nopriv_stripe_add_card', 'stripe_add_card'); // If the user in not logged in

add_action('admin_post_stripe_delete_card', 'stripe_delete_card'); // If the user is logged in
add_action('admin_post_nopriv_stripe_delete_card', 'stripe_delete_card'); // If the user in not logged in


add_action('admin_post_stripe_activate_card', 'stripe_activate_card'); // If the user is logged in
add_action('admin_post_nopriv_stripe_activate_card', 'stripe_activate_card'); // If the user in not logged in

add_action('admin_post_stripe_cancel_subscription', 'stripe_cancel_subscription'); // If the user is logged in
add_action('admin_post_nopriv_stripe_cancel_subscription', 'stripe_cancel_subscription'); // If the user in not logged in 

add_action('admin_post_stripe_change_card_for_subscription', 'stripe_change_card_for_subscription'); // If the user is logged in
add_action('admin_post_nopriv_stripe_change_card_for_subscription', 'stripe_change_card_for_subscription'); // If the user in not logged in 



/**
 * AJAX action callback hooks
 */
//Load More Charges
add_action('wp_ajax_doenanova_load_more_charges', 'doenanova_load_more_charges_ajax');
add_action('wp_ajax_nopriv_doenanova_load_more_charges', 'doenanova_load_more_charges_ajax');

// //Load More Recurring Donations
// add_action('wp_ajax_doenanova_load_more_recurring_donations', 'doenanova_load_more_recurring_donations_ajax');
// add_action('wp_ajax_nopriv_doenanova_load_more_recurring_donations', 'doenanova_load_more_recurring_donations_ajax');

//Load More Cards
add_action('wp_ajax_doenanova_load_more_cards', 'doenanova_load_more_cards_ajax');
add_action('wp_ajax_nopriv_doenanova_load_more_cards', 'doenanova_load_more_cards_ajax');
