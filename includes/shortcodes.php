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
	wp_enqueue_script('functions-js');
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
function doe_na_nova_header_shortcode($atts)
{
	// Enqueue CSS when this shortcode loaded. 
	wp_enqueue_style('doenanova-syles');

	// Outputs the HTML to replace short code
	ob_start();
	include 'doe-na-nova-header.php';
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






/**
 * OUTPUTS CURRENCY SYMBOLS (doe-na-nova-form-doar.php #amount_wrap .currency)
 */
function doe_na_nova_currency_symbol()
{
	global $doenanova_options;

	$currency = $doenanova_options['currency'];

	if ($currency == 'brl') {
		return 'R$';
	} else if ($currency == 'usd') {
		return '$';
	} else if ($currency == 'ars') {
		return '$';
	} else if ($currency == 'aud') {
		return '$';
	} else if ($currency == 'bob') {
		return 'Bs.';
	} else if ($currency == 'cad') {
		return '$';
	} else if ($currency == 'chf') {
		return '₣';
	} else if ($currency == 'clp') {
		return '$';
	} else if ($currency == 'cop') {
		return '$';
	} else if ($currency == 'crc') {
		return '₡';
	} else if ($currency == 'dkk') {
		return 'kr';
	} else if ($currency == 'dop') {
		return '$';
	} else if ($currency == 'eur') {
		return '€';
	} else if ($currency == 'gbp') {
		return '£';
	} else if ($currency == 'ils') {
		return '₪';
	} else if ($currency == 'mxn') {
		return '$';
	} else if ($currency == 'nok') {
		return 'kr';
	} else if ($currency == 'nzd') {
		return '$';
	} else if ($currency == 'pab') {
		return 'B/.';
	} else if ($currency == 'pen') {
		return 'S/.';
	} else if ($currency == 'pyg') {
		return '₲';
	} else if ($currency == 'sek') {
		return 'kr';
	} else if ($currency == 'uyu') {
		return '$';
	}
}







/**
 * OUTPUTS CSS STYLE FOR all DIVs id="doenanova-wrap" REQUIRED FOR FADE IN AND ZOOM OUT - see doenanova-styles.less @animation-duration and functions.js doenanova_wrap_fadein()
 */
function doenanova_wrap_fadein()
{
	echo 'style="transform: scale(1.1); opacity: 0;"';
}





/**
 * Function to change email address
 */
function wpb_sender_email($original_email_address)
{
	return get_bloginfo('admin_email');
}

// Function to change sender name
function wpb_sender_name($original_email_from)
{
	return get_bloginfo('name');
}

// Hooking up our functions to WordPress filters 
add_filter('wp_mail_from', 'wpb_sender_email');
add_filter('wp_mail_from_name', 'wpb_sender_name');





/**
 * Ajax Callback doenanova_load_more_charges
 */
function doenanova_load_more_charges_ajax()
{

	global $stripe_publishable_key;
	global $stripe_secret_key;
	global $stripe_is_LIVE_mode;
	global $stripe_is_TEST_mode;

	if (get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true) && isset($stripe_is_LIVE_mode)) {
		$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true);
	} else if (get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true) && isset($stripe_is_TEST_mode)) {
		$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true);
	} else {
		$customer_id = false;
	}


	//Respond with JSON content
	header('Content-Type: application/json');

	// Authenticate to the Stripe API
	\Stripe\Stripe::setApiKey($stripe_secret_key);

	//Last charge loaded
	$last_charge = $_POST['last_charge'];

	// Data to return
	$return_data = array();

	// Success or failure
	$return_data["success"] = false;
	$return_data["has_more_charges"] = false;

	try {

		$charges = \Stripe\Charge::all(array(
			"customer" => $customer_id,
			"limit" => 10,
			"starting_after" => $last_charge
		));


		foreach ($charges->data as $charge) {

			$json_decoded = json_decode($charge);

			$return_data[] = array(
				'charge_id' => $charge->id,
				'charge_status' => $charge->status,
				'charge_date' => $charge->created,
				'charge_purpose' => $charge->metadata->Purpose,
				'charge_frequency' => $charge->metadata->Frequency,
				'charge_brand' => $charge->source->brand,
				'charge_last_4' => $charge->source->last4,
				'charge_amount' => $charge->amount / 100,
			);
		}

		if ($charges->has_more) {
			$return_data["has_more_charges"] = true;
		}

		$return_data["success"] = true;



		//Invalid request errors arise when your request has invalid parameters.
	} catch (\Stripe\Error\InvalidRequest $e) {
		error_log("CRITICAL ERROR: Stripe InvalidRequest");
		error_log('Stripe InvalidRequest - httpStatus:' . $e->getHttpStatus());
		$body = $e->getJsonBody();
		$error_info = $body['error'];
		if (isset($error_info['message'])) {
			error_log('Stripe InvalidRequest - message:' . $error_info['message']);
		}
	} finally {
		echo json_encode($return_data);
		wp_die(); // required. to end AJAX request.
	}
}






/**
 * Ajax Callback doenanova_load_more_recurring_donations
 */
function doenanova_load_more_recurring_donations_ajax()
{

	global $stripe_publishable_key;
	global $stripe_secret_key;
	global $stripe_is_LIVE_mode;
	global $stripe_is_TEST_mode;

	if (get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true) && isset($stripe_is_LIVE_mode)) {
		$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true);
	} else if (get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true) && isset($stripe_is_TEST_mode)) {
		$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true);
	} else {
		$customer_id = false;
	}


	//Respond with JSON content
	header('Content-Type: application/json');

	// Authenticate to the Stripe API
	\Stripe\Stripe::setApiKey($stripe_secret_key);

	//Last subscription loaded
	$last_subscription = $_POST['last_subscription'];

	// Data to return
	$return_data = array();

	// Success or failure
	$return_data["success"] = false;
	$return_data["has_more_subscriptions"] = false;

	try {

		$subscriptions = \Stripe\Subscription::all(array(
			"customer" => $customer_id,
			"status" => 'active',
			"limit" => 10,
			"starting_after" => $last_subscription
		));

		$customer = \Stripe\Customer::retrieve($customer_id);

		if (isset($customer->sources->data[0])) {
			$customer_cardBrand = strtolower($customer->sources->data[0]->brand);
			$customer_cardLast4 = $customer->sources->data[0]->last4;
		} else {
			$customer_cardBrand = false;
			$customer_cardLast4 = __('No saved card.', 'doenanova');
		}


		foreach ($subscriptions->data as $subscription) {

			$json_decoded = json_decode($subscription);

			$return_data[] = array(
				'subscription_id' => $subscription->id,
				'subscription_date' => $subscription->created,
				'subscription_status' => $subscription->status,
				'subscription_purpose' => $subscription->metadata->Purpose,
				'subscription_interval' => $subscription->plan->interval,
				'customer_cardBrand' => $customer_cardBrand,
				'customer_cardLast4' => $customer_cardLast4,
				'subscription_planAmount' => $subscription->plan->amount / 100,
				'subscription_nextBilling' => $subscription->current_period_end,
			);
		}

		if ($subscriptions->has_more) {
			$return_data["has_more_subscriptions"] = true;
		}

		$return_data["success"] = true;



		//Invalid request errors arise when your request has invalid parameters.
	} catch (\Stripe\Error\InvalidRequest $e) {
		error_log("CRITICAL ERROR: Stripe InvalidRequest");
		error_log('Stripe InvalidRequest - httpStatus:' . $e->getHttpStatus());
		$body = $e->getJsonBody();
		$error_info = $body['error'];
		if (isset($error_info['message'])) {
			error_log('Stripe InvalidRequest - message:' . $error_info['message']);
		}
	} finally {
		echo json_encode($return_data);
		wp_die(); // required. to end AJAX request.
	}
}





/*



function registration_form( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio ) {
    echo '
    <form action="' . $_SERVER['REQUEST_URI'] . '" method="post">
    <div>
    <label for="username">Username <strong>*</strong></label>
    <input class="doenanova-input" type="text" name="username" value="' . ( isset( $_POST['username'] ) ? $username : null ) . '">
    </div>
     
    <div>
    <label for="password">Password <strong>*</strong></label>
    <input class="doenanova-input" type="password" name="password" value="' . ( isset( $_POST['password'] ) ? $password : null ) . '">
    </div>
     
    <div>
    <label for="email">Email <strong>*</strong></label>
    <input class="doenanova-input" type="text" name="email" value="' . ( isset( $_POST['email']) ? $email : null ) . '">
    </div>
     
    <div>
    <label for="website">Website</label>
    <input class="doenanova-input" type="text" name="website" value="' . ( isset( $_POST['website']) ? $website : null ) . '">
    </div>
     
    <div>
    <label for="firstname">First Name</label>
    <input class="doenanova-input" type="text" name="fname" value="' . ( isset( $_POST['fname']) ? $first_name : null ) . '">
    </div>
     
    <div>
    <label for="website">Last Name</label>
    <input class="doenanova-input" type="text" name="lname" value="' . ( isset( $_POST['lname']) ? $last_name : null ) . '">
    </div>
     
    <div>
    <label for="nickname">Nickname</label>
    <input class="doenanova-input" type="text" name="nickname" value="' . ( isset( $_POST['nickname']) ? $nickname : null ) . '">
    </div>
     
    <div>
    <label for="bio">About / Bio</label>
    <textarea class="doenanova-input" name="bio">' . ( isset( $_POST['bio']) ? $bio : null ) . '</textarea>
    </div>
    <input class="doenanova-btn" type="submit" name="submit" value="Register"/>
    </form>
    ';
}


function registration_validation( $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio )  {
	
	//Instantiate the WP_Error class and make the instance variable global so it can be access outside the scope of the function.
	global $reg_errors;
	$reg_errors = new WP_Error;
	
	//If empty, we append the error message to the global WP_Error class.
	if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
	    $reg_errors->add('field', 'Required form field is missing');
	}
	
	// We also check to make sure the number of username characters is not less than 4.
	if ( 4 > strlen( $username ) ) {
	    $reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
	}
	
	//Check if the username is already registered.
	if ( username_exists( $username ) )
	    $reg_errors->add('user_name', 'Sorry, that username already exists!');
	
	//Employ the services of WordPress validate_username function to make sure the username is valid.
	if ( ! validate_username( $username ) ) {
	    $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
	}
	
	//Ensure the password entered by users is not less than 5 characters.
	if ( 5 > strlen( $password ) ) {
	    $reg_errors->add( 'password', 'Password length must be greater than 5' );
	}
	
	//Check if the email is a valid email.
	if ( !is_email( $email ) ) {
	    $reg_errors->add( 'email_invalid', 'Email is not valid' );
	}
	
	//Check if the email is already registered.
	if ( email_exists( $email ) ) {
	    $reg_errors->add( 'email', 'Email Already in use' );
	}
	
	//If the website field is filled, check to see if it is valid.
	if ( ! empty( $website ) ) {
	    if ( ! filter_var( $website, FILTER_VALIDATE_URL ) ) {
	        $reg_errors->add( 'website', 'Website is not a valid URL' );
	    }
	}
	
	//Finally, we loop through the errors in our WP_Error instance and display the individual error.
	if ( is_wp_error( $reg_errors ) ) {
    foreach ( $reg_errors->get_error_messages() as $error ) {
      echo '<div>';
      echo '<strong>ERROR</strong>:';
      echo $error . '<br/>';
      echo '</div>';
    }
	}
	
}



function complete_registration() {
    global $reg_errors, $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
    if ( 1 > count( $reg_errors->get_error_messages() ) ) {
        $userdata = array(
        'user_login'    =>   $username,
        'user_email'    =>   $email,
        'user_pass'     =>   $password,
        'user_url'      =>   $website,
        'first_name'    =>   $first_name,
        'last_name'     =>   $last_name,
        'nickname'      =>   $nickname,
        'description'   =>   $bio,
        );
        $user = wp_insert_user( $userdata );
        echo 'Registration complete. Goto <a href="' . get_site_url() . '/wp-login.php">login page</a>.';   
    }
}
*/



/*
function custom_registration_function() {
    if ( isset($_POST['submit'] ) ) {
        registration_validation(
        $_POST['username'],
        $_POST['password'],
        $_POST['email'],
        $_POST['website'],
        $_POST['fname'],
        $_POST['lname'],
        $_POST['nickname'],
        $_POST['bio']
        );
         
        // sanitize user form input
        global $username, $password, $email, $website, $first_name, $last_name, $nickname, $bio;
        $username   =   sanitize_user( $_POST['username'] );
        $password   =   esc_attr( $_POST['password'] );
        $email      =   sanitize_email( $_POST['email'] );
        $website    =   esc_url( $_POST['website'] );
        $first_name =   sanitize_text_field( $_POST['fname'] );
        $last_name  =   sanitize_text_field( $_POST['lname'] );
        $nickname   =   sanitize_text_field( $_POST['nickname'] );
        $bio        =   esc_textarea( $_POST['bio'] );
 
        // call @function complete_registration to create the user
        // only when no WP_error is found
        complete_registration(
        $username,
        $password,
        $email,
        $website,
        $first_name,
        $last_name,
        $nickname,
        $bio
        );
    }
 
    registration_form(
        $username,
        $password,
        $email,
        $website,
        $first_name,
        $last_name,
        $nickname,
        $bio
        );
}
*/


// The callback function that will replace [book]
/*
function doe_na_nova_register_form_shortcode() {
    ob_start();
    custom_registration_function();
    return ob_get_clean();
}
*/






/**
 * Registers doe-na-nova-nav
 */
function register_doenanova_menu()
{
	register_nav_menus(
		array(
			'doe-na-nova-nav' => 'Doe na Nova Nav'
		)
	);
}

/*
 * Makes it possible to add link_class to wp_nav_menu
 */
function add_menu_link_class($atts, $item, $args)
{
	if (property_exists($args, 'link_class')) {
		$atts['class'] = $args->link_class;
	}
	return $atts;
}
add_filter('nav_menu_link_attributes', 'add_menu_link_class', 1, 3);



/*
 * Outputs "doenanova-app" to URLs
 */
function doenanova_app_slug()
{

	if (isset($_POST['current_url'])) {
		$current_url = $_POST['current_url'];
	} else {
		$current_url = get_permalink();
	}

	if (strpos($current_url, 'doenanova-app') !== false) {
		return "/doenanova-app/";
	} else {
		return "/";
	}
}



function wppb_forms_styles()
{
	// Enqueue CSS when this shortcode loaded.
	wp_enqueue_style('wppb-forms');
}
