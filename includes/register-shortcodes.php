<?php

/**
 * Init hook. Registers shortcode doe-na-nova-form-doar.
 * @since 1.0.0
 */
function doe_na_nova_form_init()
{
	add_shortcode('doe-na-nova-form-doar', 'doe_na_nova_donation_form_shortcode');

	add_shortcode('doe-na-nova-header', 'doe_na_nova_header_shortcode');

	add_shortcode('doe-na-nova-resultado-doacao', 'doe_na_nova_donation_result_shortcode');

	add_shortcode('doe-na-nova-cartoes', 'doe_na_nova_saved_cards_shortcode');

	add_shortcode('doe-na-nova-recorrentes', 'doe_na_nova_recurring_donations_shortcode');

	add_shortcode('doe-na-nova-transacoes', 'doe_na_nova_recent_transactions_shortcode');

	// Register a new shortcode
	//add_shortcode( 'doe-na-nova-register-form', 'doe_na_nova_register_form_shortcode' );

	//Hooking into Profile Builder plugin to load wppb_forms_styles when the plugin calls for it's styles
	add_action('wp_print_styles', 'wppb_forms_styles');
}
