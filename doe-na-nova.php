<?php
/*
 * Plugin Name:		Doe na Nova
 * Plugin URI:		https://novaigreja.com/doe
 * Description:		Plataforma da Nova Igreja para receber doações online.
 * Version:			1.3
 * Author:			Nova Digital Team
 * Author URI:		https://novaigreja.com
 * License:			GPL-2.0+
 * License URI:		http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Not called within Wordpress framework
if (!defined('WPINC')) {
	die;
}


/***************
 * global variables
 ***************/

$doenanova_prefix = 'doenanova_';
$doenanova_plugin_name = 'Doe na Nova';


// retrieve our plugin settings from the options table
$doenanova_options = get_option('doenanova_settings');


ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '../../logs/error.log');
error_log('Doe na Nova WordPress plugin');

//Localise (Translate into other languages)
load_plugin_textdomain('doenanova', false, dirname(plugin_basename(__FILE__)) . '/languages/');


/***************
 * includes
 ***************/

//Stripe
include_once dirname(__FILE__) . '/includes/stripe-setup.php';
include_once($path_to_stripe_lib . 'init.php');

//doenanova
include_once dirname(__FILE__) . '/includes/admin-page.php';
include_once dirname(__FILE__) . '/includes/register-shortcodes.php';
include_once dirname(__FILE__) . '/includes/register-js.php';
include_once dirname(__FILE__) . '/includes/register-css.php';
include_once dirname(__FILE__) . '/includes/shortcodes.php';
include_once dirname(__FILE__) . '/includes/functions.php';
include_once dirname(__FILE__) . '/includes/stripe-checkout.php';
include_once dirname(__FILE__) . '/includes/add-actions.php';
