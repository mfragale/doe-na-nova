<?php

/**
 * Ajax Callback $_POST['action'] = stripe_checkout
 * @since 1.0.0
 */


/**
 * MAIN DONATION FORM CHECKOUT FLOW
 */
function stripe_checkout()
{
	global $stripe_secret_key;
	global $stripe_is_TEST_mode;
	global $stripe_is_LIVE_mode;

	//to retrive the API keys from admin page
	global $doenanova_options;

	\Stripe\Stripe::setApiKey($stripe_secret_key);

	$return_data = array();
	$return_data["success"] = false;

	if (isset($_POST['stripeToken'])) {
		$token = $_POST['stripeToken'];
	}
	$amount = $_POST['amount'];

	$frequency = $_POST['frequency'];
	$purpose = $_POST['purpose'];

	$user_email = $_POST['user_email'];
	$user_name = $_POST['user_name'];

	$current_url = $_POST['current_url'];

	$currency = $doenanova_options['currency'];


	if (get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true) && isset($stripe_is_LIVE_mode)) {
		$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true);
	} else if (get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true) && isset($stripe_is_TEST_mode)) {
		$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true);
	} else {
		$customer_id = false;
	}


	if (!$customer_id) {
		try {
			$customer = \Stripe\Customer::create(array(
				'email' => $user_email,
				'source' => $token,
				'description' => $user_name,
			));

			$customer_id = $customer->id;

			if (isset($stripe_is_LIVE_mode)) {
				update_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', $customer_id);
			} else if (isset($stripe_is_TEST_mode)) {
				update_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', $customer_id);
			}
		} catch (\Stripe\Error\Card $e) {
			// Since it's a decline, \Stripe\Error\Card will be caught
			$return_data["success"] = false;
			$body = $e->getJsonBody();
			$err  = $body['error'];
			wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
			exit;
			die();
		}
	}



	if ($customer_id) {


		//If the customer adds a card and then deletes it, he will have a $customer_id but no source
		$source = \Stripe\Customer::retrieve($customer_id)->sources->all(array(
			'object' => 'card'
		));
		if (!$source->data) {
			$customer = \Stripe\Customer::retrieve($customer_id);

			try {
				$customer->sources->create(array("source" => $token));
			} catch (\Stripe\Error\Card $e) {
				// Since it's a decline, \Stripe\Error\Card will be caught
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\RateLimit $e) {
				// Too many requests made to the API too quickly
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\InvalidRequest $e) {
				// Invalid parameters were supplied to Stripe's API
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\Authentication $e) {
				// Authentication with Stripe's API failed
				// (maybe you changed API keys recently)
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\ApiConnection $e) {
				// Network communication with Stripe failed
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\Base $e) {
				// Display a very generic error to the user, and maybe send yourself an email
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (Exception $e) {
				// Something else happened, completely unrelated to Stripe
				$return_data["success"] = false;
				$body = $e->getMessage();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			}
		}



		if ($frequency == 'one time') {

			//ONE TIME DONATIONS
			try {
				$charge = \Stripe\Charge::create(array(
					"customer" => $customer_id,
					"amount" => $amount * 100,
					"currency" => $currency,
					"receipt_email" => $user_email,
					"metadata" => array("Purpose" => $purpose, "Frequency" => $frequency),
				));

				$return_data["success"] = true;

				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&amount=' . $amount . '&user_email=' . $user_email . '&user_name=' . $user_name . '&customer_id=' . $customer_id . '');
				exit;

				die();
			} catch (\Stripe\Error\Card $e) {
				// Since it's a decline, \Stripe\Error\Card will be caught
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\RateLimit $e) {
				// Too many requests made to the API too quickly
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\InvalidRequest $e) {
				// Invalid parameters were supplied to Stripe's API
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\Authentication $e) {
				// Authentication with Stripe's API failed
				// (maybe you changed API keys recently)
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\ApiConnection $e) {
				// Network communication with Stripe failed
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\Base $e) {
				// Display a very generic error to the user, and maybe send yourself an email
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (Exception $e) {
				// Something else happened, completely unrelated to Stripe
				$return_data["success"] = false;
				$body = $e->getMessage();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			}
		} else {

			//RECURRING DONATIONS
			try {
				$plan = \Stripe\Plan::create(array(
					"amount" => $amount * 100,
					"interval" => $frequency,
					"product" => array(
						"name" => $amount . "/" . $frequency . "/" . $customer_id
					),
					"currency" => $currency,
					"id" => $amount . "_" . $frequency . "_" . $customer_id
				));

				$subscription = \Stripe\Subscription::create(array(
					"customer" => $customer_id,
					"items" => array(
						array(
							"plan" => $plan,
						),
					),
					"metadata" => array("Purpose" => $purpose, "Frequency" => $frequency),
				));

				$invoice = \Stripe\Invoice::all(array(
					"limit" => 1,
					"customer" => $customer_id
				));

				$chargeId = $invoice->data[0]->charge;

				$ch = \Stripe\Charge::retrieve($chargeId);
				$ch->metadata->Purpose = $purpose;
				$ch->metadata->Frequency = $frequency;
				$ch->save();

				$return_data["success"] = true;

				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&amount=' . $amount . '&user_email=' . $user_email . '&user_name=' . $user_name . '&customer_id=' . $customer_id . '');
				exit;

				die();
			} catch (\Stripe\Error\Card $e) {
				// Since it's a decline, \Stripe\Error\Card will be caught
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&status=' . $e->getHttpStatus() . '&type=' . $err['type'] . '&code=' . $err['code'] . '&param=' . $err['param'] . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\RateLimit $e) {
				// Too many requests made to the API too quickly
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&status=' . $e->getHttpStatus() . '&type=' . $err['type'] . '&code=' . $err['code'] . '&param=' . $err['param'] . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\InvalidRequest $e) {
				// Invalid parameters were supplied to Stripe's API
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&status=' . $e->getHttpStatus() . '&type=' . $err['type'] . '&code=' . $err['code'] . '&param=' . $err['param'] . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\Authentication $e) {
				// Authentication with Stripe's API failed
				// (maybe you changed API keys recently)
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&status=' . $e->getHttpStatus() . '&type=' . $err['type'] . '&code=' . $err['code'] . '&param=' . $err['param'] . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\ApiConnection $e) {
				// Network communication with Stripe failed
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&status=' . $e->getHttpStatus() . '&type=' . $err['type'] . '&code=' . $err['code'] . '&param=' . $err['param'] . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Error\Base $e) {
				// Display a very generic error to the user, and maybe send yourself an email
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&status=' . $e->getHttpStatus() . '&type=' . $err['type'] . '&code=' . $err['code'] . '&param=' . $err['param'] . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (Exception $e) {
				// Something else happened, completely unrelated to Stripe
				$return_data["success"] = false;
				$body = $e->getMessage();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&status=' . $e->getMessage() . '&type=' . $err['type'] . '&code=' . $err['code'] . '&param=' . $err['param'] . '&customer_id=' . $customer_id . '');
				exit;
				die();
			}
		}
	} //if ( $customer_id )	
}








/**
 * DELETE CARD ACTION FLOW
 */
function stripe_delete_card()
{
	global $stripe_secret_key;
	\Stripe\Stripe::setApiKey($stripe_secret_key);

	global $stripe_is_TEST_mode;
	global $stripe_is_LIVE_mode;

	//to retrive the API keys from admin page
	global $doenanova_options;

	$current_url = $_POST['current_url'];

	$return_data = array();
	$return_data["success"] = false;

	if (get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true) && isset($stripe_is_LIVE_mode)) {
		$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true);
	} else if (get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true) && isset($stripe_is_TEST_mode)) {
		$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true);
	} else {
		$customer_id = false;
	}

	$card_id = $_POST['card_id'];

	try {

		$customer = \Stripe\Customer::retrieve($customer_id);
		$customer->sources->retrieve($card_id)->delete();

		$return_data["success"] = true;

		wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_saved_card'] . '?success=' . $return_data["success"] . '&deletecard=1&customer_id=' . $customer_id . '');
		exit;

		die();
	} catch (\Stripe\Error\Card $e) {
		$body = $e->getJsonBody();
		$err  = $body['error'];
		$error = $err['message'];
	}
}






/**
 * CANCEL SUBSCRIPTION ACTION FLOW
 */
function stripe_cancel_subscription()
{
	global $stripe_secret_key;
	\Stripe\Stripe::setApiKey($stripe_secret_key);

	global $stripe_is_TEST_mode;
	global $stripe_is_LIVE_mode;

	//to retrive the API keys from admin page
	global $doenanova_options;

	$current_url = $_POST['current_url'];

	$return_data = array();
	$return_data["success"] = false;

	if (get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true) && isset($stripe_is_LIVE_mode)) {
		$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true);
	} else if (get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true) && isset($stripe_is_TEST_mode)) {
		$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true);
	} else {
		$customer_id = false;
	}

	$subscriptionId = $_POST['subscription_id'];

	try {

		$sub = \Stripe\Subscription::retrieve($subscriptionId);
		$sub->cancel();

		$return_data["success"] = true;

		wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_recurring_donations'] . '?success=' . $return_data["success"] . '&cancelsubs=1&customer_id=' . $customer_id . '');
		exit;

		die();
	} catch (\Stripe\Error\Card $e) {
		$body = $e->getJsonBody();
		$err  = $body['error'];
		$error = $err['message'];
	}
}
