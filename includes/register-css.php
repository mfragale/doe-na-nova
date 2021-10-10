<?php

/**
 * Register CSS files
 * @since 1.0.0
 */

function doe_na_nova_form_css()
{
	wp_register_style(
		'doenanova-syles',
		plugin_dir_url(__FILE__) . 'scss/dist/doe-na-nova-syles-min.css',
		null,
		'1.1'
	);

	wp_register_style(
		'wppb-forms',
		plugin_dir_url(__FILE__) . 'scss/wppb-forms.min.css',
		null,
		'1.1'
	);
}
