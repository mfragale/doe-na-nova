<?php

/**
 * Shortcode processing for doe-na-nova-form-doar
 * 
 * @since 1.0.0
 * @param array $atts The attributes in the shortcode
 */



/**
 * MAIN DONATION FORM SHORTCODE
 */
function doe_na_nova_donation_form_shortcode($atts)
{
	global $stripe_publishable_key;

	// Enqueue JS when this shortcode loaded.
	wp_enqueue_script('my-functions-js');
	wp_enqueue_script('checkout-js');
	wp_enqueue_script('font-awesome');

	// Enqueue CSS when this shortcode loaded.
	wp_enqueue_style('doenanova-syles');

	// Outputs the HTML to replace short code
	// The custom shortcode was being executed when saving page in wp-admin, so: 
	// https://wordpress.stackexchange.com/questions/140466/custom-shortcode-being-executed-when-saving-page-in-wp-admin

	ob_start();
	include 'doe-na-nova-form-doar.php';
	return ob_get_clean();
}


/**
 * doenanova HEADER SHORTCODE
 */
// function doe_na_nova_header_shortcode($atts)
// {
// 	// Enqueue CSS when this shortcode loaded. 
// 	wp_enqueue_style('doenanova-syles');

// 	// Outputs the HTML to replace short code
// 	ob_start();
// 	include 'doe-na-nova-nav.php';
// 	return ob_get_clean();
// }


/**
 * doenanova PERFIL SHORTCODE
 */
function doe_na_nova_perfil_shortcode($atts)
{
	// Enqueue CSS when this shortcode loaded. 
	wp_enqueue_style('doenanova-syles');

	// Outputs the HTML to replace short code
	ob_start();
	include 'doe-na-nova-perfil.php';
	return ob_get_clean();
}


/**
 * OUTPUTS DONATION RESULTS SHORTCODE
 */
function doe_na_nova_donation_result_shortcode()
{
	global $stripe_publishable_key;

	// Enqueue JS when this shortcode loaded.
	wp_enqueue_script('functions-js');
	wp_enqueue_script('font-awesome');

	// Enqueue CSS when this shortcode loaded. 
	wp_enqueue_style('doenanova-syles');

	// Outputs the HTML to replace short code
	ob_start();
	include 'doe-na-nova-resultado-doacao.php';
	return ob_get_clean();
}


/**
 * OUTPUTS SAVED CARDS SHORTCODE
 */
function doe_na_nova_saved_cards_shortcode($atts)
{
	global $stripe_publishable_key;

	// Enqueue JS when this shortcode loaded.
	wp_enqueue_script('functions-js');
	wp_enqueue_script('font-awesome');

	// Enqueue CSS when this shortcode loaded. 
	wp_enqueue_style('doenanova-syles');

	// Outputs the HTML to replace short code
	ob_start();
	include 'doe-na-nova-cartoes.php';
	return ob_get_clean();
}


/**
 * OUTPUTS RECURRING DONATIONS SHORTCODE
 */
function doe_na_nova_recurring_donations_shortcode($atts)
{
	global $stripe_publishable_key;

	// Enqueue JS when this shortcode loaded.
	wp_enqueue_script('functions-js');
	wp_enqueue_script('ajax_load_more_recurring_donations-js');
	wp_enqueue_script('font-awesome');

	// Enqueue CSS when this shortcode loaded. 
	wp_enqueue_style('doenanova-syles');

	// Outputs the HTML to replace short code
	ob_start();
	include 'doe-na-nova-recorrentes.php';
	return ob_get_clean();
}


/**
 * OUTPUTS RECENT TRANSACTIONS SHORTCODE
 */
function doe_na_nova_recent_transactions_shortcode($atts)
{
	global $stripe_publishable_key;

	// Enqueue JS when this shortcode loaded.
	wp_enqueue_script('functions-js');
	wp_enqueue_script('ajax_load_more_charges-js');
	wp_enqueue_script('font-awesome');

	// Enqueue CSS when this shortcode loaded. 
	wp_enqueue_style('doenanova-syles');

	// Outputs the HTML to replace short code
	ob_start();
	include 'doe-na-nova-transacoes.php';
	return ob_get_clean();
}
