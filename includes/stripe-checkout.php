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
	global $customer_id;

	//to retrive the API keys from admin page
	global $doenanova_options;

	$stripe = new \Stripe\StripeClient(
		$stripe_secret_key
	);

	$return_data = array();
	$return_data["success"] = false;

	if (isset($_POST['stripeToken'])) {
		$token = $_POST['stripeToken'];
	}
	$amount = $_POST['amount'];

	$frequency = $_POST['frequency'];
	$purpose = $_POST['purpose'];
	//$platformAccountId = 'acct_17wccsEYlIfdDhnn';

	$user_email = $_POST['user_email'];
	$user_name = $_POST['user_name'];

	//$current_url = $_POST['current_url'];

	$currency = $doenanova_options['currency'];


	// if (get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true) && isset($stripe_is_LIVE_mode)) {
	// 	$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true);
	// } else if (get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true) && isset($stripe_is_TEST_mode)) {
	// 	$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true);
	// } else {
	// 	$customer_id = false;
	// }


	if (!$customer_id) {
		try {
			$customer = $stripe->customers->create([
				'email' => $user_email,
				'source' => $token,
				'name' => $user_name,
			]);

			$customer_id = $customer->id;

			if (isset($stripe_is_LIVE_mode)) {
				update_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', $customer_id);
			} else if (isset($stripe_is_TEST_mode)) {
				update_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', $customer_id);
			}
		} catch (\Stripe\Exception\CardException $e) {
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
		$source = $stripe->customers->allSources(
			$customer_id,
			['object' => 'card']
		);
		if (!$source->data) {
			try {
				$source = $stripe->customers->createSource(
					$customer_id,
					['source' => $token]
				);
			} catch (\Stripe\Exception\CardException $e) {
				// Since it's a decline, \Stripe\Error\Card will be caught
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\RateLimitException $e) {
				// Too many requests made to the API too quickly
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\InvalidRequestException $e) {
				// Invalid parameters were supplied to Stripe's API
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\AuthenticationException $e) {
				// Authentication with Stripe's API failed
				// (maybe you changed API keys recently)
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\ApiConnectionException $e) {
				// Network communication with Stripe failed
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\ApiErrorException $e) {
				// Display a very generic error to the user, and maybe send yourself an email
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (Exception $e) {
				// Something else happened, completely unrelated to Stripe
				$return_data["success"] = false;
				$body = $e->getMessage();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			}
		}

		// Nova Igreja Account
		//'transfer_data' => ['destination' => 'acct_17wccsEYlIfdDhnn']

		// Nova Igreja Joinville
		//'transfer_data' => ['destination' => 'acct_1LFUM8LggNLHjwWn']

		$purposeObj = $stripe->accounts->retrieve(
			$purpose,
			[]
		);

		if ($frequency == 'one time') {

			//ONE TIME DONATIONS
			try {
				$charge = $stripe->charges->create([
					"customer" => $customer_id,
					"amount" => $amount * 100,
					"currency" => $currency,
					"receipt_email" => $user_email,
					"metadata" => ['Purpose' => $purposeObj->settings->dashboard->display_name, 'Frequency' => $frequency,],
					'description' => 'Doação única',
					'transfer_data' => ['destination' => $purpose],
				]);

				// If Stripe Connected Account ID (Purpose) is not equal to Main Platform Account ID, then transfer the charge to Stripe Connected Account.
				// if ($purpose != $platformAccountId) {
				// 	$transfer = $stripe->transfers->create([
				// 		"amount" => $amount * 100,
				// 		"currency" => $currency,
				// 		"source_transaction" => $charge->id,
				// 		"destination" => $purpose,
				// 	]);
				// }

				$return_data["success"] = true;

				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&amount=' . $amount . '&user_email=' . $user_email . '&user_name=' . $user_name . '&customer_id=' . $customer_id . '');
				exit;

				die();
			} catch (\Stripe\Exception\CardException $e) {
				// Since it's a decline, \Stripe\Error\Card will be caught
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["type"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\RateLimitException $e) {
				// Too many requests made to the API too quickly
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\InvalidRequestException $e) {
				// Invalid parameters were supplied to Stripe's API
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\AuthenticationException $e) {
				// Authentication with Stripe's API failed
				// (maybe you changed API keys recently)
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\ApiConnectionException $e) {
				// Network communication with Stripe failed
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\ApiErrorException $e) {
				// Display a very generic error to the user, and maybe send yourself an email
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (Exception $e) {
				// Something else happened, completely unrelated to Stripe
				$return_data["success"] = false;
				$body = $e->getMessage();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
				exit;
				die();
			}
		} else {

			//RECURRING DONATIONS
			try {
				$product = $stripe->products->create([
					'name' => $amount . " - " . $frequency,
					'description' => 'Doação recorrente',
					'metadata' => ['Purpose' => $purposeObj->settings->dashboard->display_name, 'Frequency' => $frequency,],
				]);

				$price = $stripe->prices->create([
					'unit_amount' => $amount * 100,
					'currency' => $currency,
					'recurring' => ['interval' => $frequency],
					'product' => $product->id,
					'metadata' => ['Purpose' => $purposeObj->settings->dashboard->display_name, 'Frequency' => $frequency,],
				]);

				$subscription = $stripe->subscriptions->create([
					'customer' => $customer_id,
					'items' => [
						['price' => $price->id],
					],
					'default_payment_method' => $source->data[0]->id,
					'metadata' => ['Purpose' => $purposeObj->settings->dashboard->display_name, 'Frequency' => $frequency,],
					'transfer_data' => ['destination' => $purpose],
				]);

				// $invoiceId = $subscription->latest_invoice;

				// $stripe->invoices->update(
				// 	$invoiceId,
				// 	[
				// 		'metadata' => [
				// 			'Purpose' => $purposeObj->settings->dashboard->display_name,
				// 			'Frequency' => $frequency,
				// 		]
				// 	]
				// );

				// $invoiceObj = $stripe->invoices->retrieve(
				// 	$invoiceId,
				// 	[]
				// );

				// // This step is done via Webhook for automatic subscription updates
				// $stripe->paymentIntents->update(
				// 	$invoiceObj->payment_intent,
				// 	[
				// 		'metadata' => [
				// 			'Purpose' => $purposeObj->settings->dashboard->display_name,
				// 			'Frequency' => $frequency,
				// 		],
				// 		'description' => 'Doação recorrente - 1º ciclo',
				// 	]
				// );

				// // If Stripe Connected Account ID (Purpose) is not equal to Main Platform Account ID, then transfer the charge to Stripe Connected Account.
				// if ($purpose != $platformAccountId) {
				// 	$transfer = $stripe->transfers->create([
				// 		"amount" => $amount * 100,
				// 		"currency" => $currency,
				// 		"source_transaction" => $invoiceObj->charge,
				// 		"destination" => $purpose,
				// 	]);
				// }


				$return_data["success"] = true;

				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&amount=' . $amount . '&user_email=' . $user_email . '&user_name=' . $user_name . '&customer_id=' . $customer_id . '');
				exit;

				die();
			} catch (\Stripe\Exception\CardException $e) {
				// Since it's a decline, \Stripe\Error\Card will be caught
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&status=' . $e->getHttpStatus() . '&type=' . $err['type'] . '&code=' . $err['code'] . '&param=' . $err['param'] . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\RateLimitException $e) {
				// Too many requests made to the API too quickly
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&status=' . $e->getHttpStatus() . '&type=' . $err['type'] . '&code=' . $err['code'] . '&param=' . $err['param'] . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\InvalidRequestException $e) {
				// Invalid parameters were supplied to Stripe's API
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&status=' . $e->getHttpStatus() . '&type=' . $err['type'] . '&code=' . $err['code'] . '&param=' . $err['param'] . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\AuthenticationException $e) {
				// Authentication with Stripe's API failed
				// (maybe you changed API keys recently)
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&status=' . $e->getHttpStatus() . '&type=' . $err['type'] . '&code=' . $err['code'] . '&param=' . $err['param'] . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\ApiConnectionException $e) {
				// Network communication with Stripe failed
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&status=' . $e->getHttpStatus() . '&type=' . $err['type'] . '&code=' . $err['code'] . '&param=' . $err['param'] . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (\Stripe\Exception\ApiErrorException $e) {
				// Display a very generic error to the user, and maybe send yourself an email
				$return_data["success"] = false;
				$body = $e->getJsonBody();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&status=' . $e->getHttpStatus() . '&type=' . $err['type'] . '&code=' . $err['code'] . '&param=' . $err['param'] . '&customer_id=' . $customer_id . '');
				exit;
				die();
			} catch (Exception $e) {
				// Something else happened, completely unrelated to Stripe
				$return_data["success"] = false;
				$body = $e->getMessage();
				$err  = $body['error'];
				wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?message=' . urlencode($err["message"]) . '&status=' . $e->getMessage() . '&type=' . $err['type'] . '&code=' . $err['code'] . '&param=' . $err['param'] . '&customer_id=' . $customer_id . '');
				exit;
				die();
			}
		}
	} //if ( $customer_id )	
}





/**
 * ADD CARD ACTION FLOW
 */
function stripe_add_card()
{
	global $stripe_secret_key;
	global $stripe_is_TEST_mode;
	global $stripe_is_LIVE_mode;
	global $customer_id;

	//to retrive the API keys from admin page
	global $doenanova_options;

	$stripe = new \Stripe\StripeClient(
		$stripe_secret_key
	);

	$return_data = array();
	$return_data["success"] = false;

	if (isset($_POST['stripeToken'])) {
		$token = $_POST['stripeToken'];
	}

	$user_email = $_POST['user_email'];
	$user_name = $_POST['user_name'];

	// if (get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true) && isset($stripe_is_LIVE_mode)) {
	// 	$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true);
	// } else if (get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true) && isset($stripe_is_TEST_mode)) {
	// 	$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true);
	// } else {
	// 	$customer_id = false;
	// }

	if (!$customer_id) {
		try {
			$customer = $stripe->customers->create([
				'email' => $user_email,
				'source' => $token,
				'name' => $user_name,
			]);

			$customer_id = $customer->id;

			if (isset($stripe_is_LIVE_mode)) {
				update_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', $customer_id);
			} else if (isset($stripe_is_TEST_mode)) {
				update_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', $customer_id);
			}
		} catch (\Stripe\Exception\CardException $e) {
			// Since it's a decline, \Stripe\Error\Card will be caught
			$return_data["success"] = false;
			$body = $e->getJsonBody();
			$err  = $body['error'];
			wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_saved_card'] . '?success=' . $return_data["success"] . '&message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
			exit;
			die();
		}
	}

	if ($customer_id) {
		try {
			$source = $stripe->customers->createSource(
				$customer_id,
				['source' => $token]
			);

			wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_saved_card'] . '?success=' . $return_data["success"] . '&addcard=1&customer_id=' . $customer_id . '');
			exit;

			die();
		} catch (\Stripe\Exception\CardException $e) {
			// Since it's a decline, \Stripe\Error\Card will be caught
			$return_data["success"] = false;
			$body = $e->getJsonBody();
			$err  = $body['error'];
			wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_saved_card'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
			exit;
			die();
		} catch (\Stripe\Exception\RateLimitException $e) {
			// Too many requests made to the API too quickly
			$return_data["success"] = false;
			$body = $e->getJsonBody();
			$err  = $body['error'];
			wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_saved_card'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
			exit;
			die();
		} catch (\Stripe\Exception\InvalidRequestException $e) {
			// Invalid parameters were supplied to Stripe's API
			$return_data["success"] = false;
			$body = $e->getJsonBody();
			$err  = $body['error'];
			wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_saved_card'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
			exit;
			die();
		} catch (\Stripe\Exception\AuthenticationException $e) {
			// Authentication with Stripe's API failed
			// (maybe you changed API keys recently)
			$return_data["success"] = false;
			$body = $e->getJsonBody();
			$err  = $body['error'];
			wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_saved_card'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
			exit;
			die();
		} catch (\Stripe\Exception\ApiConnectionException $e) {
			// Network communication with Stripe failed
			$return_data["success"] = false;
			$body = $e->getJsonBody();
			$err  = $body['error'];
			wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_saved_card'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
			exit;
			die();
		} catch (\Stripe\Exception\ApiErrorException $e) {
			// Display a very generic error to the user, and maybe send yourself an email
			$return_data["success"] = false;
			$body = $e->getJsonBody();
			$err  = $body['error'];
			wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_saved_card'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
			exit;
			die();
		} catch (Exception $e) {
			// Something else happened, completely unrelated to Stripe
			$return_data["success"] = false;
			$body = $e->getMessage();
			$err  = $body['error'];
			wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_saved_card'] . '?message=' . urlencode($err["message"]) . '&customer_id=' . $customer_id . '');
			exit;
			die();
		}
	} //if ( $customer_id )	

}




/**
 * DELETE CARD ACTION FLOW
 */
function stripe_delete_card()
{
	global $stripe_secret_key;
	$stripe = new \Stripe\StripeClient(
		$stripe_secret_key
	);

	// global $stripe_is_TEST_mode;
	// global $stripe_is_LIVE_mode;

	global $customer_id;

	//to retrive the API keys from admin page
	global $doenanova_options;

	//$current_url = $_POST['current_url'];

	$return_data = array();
	$return_data["success"] = false;

	// if (get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true) && isset($stripe_is_LIVE_mode)) {
	// 	$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true);
	// } else if (get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true) && isset($stripe_is_TEST_mode)) {
	// 	$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true);
	// } else {
	// 	$customer_id = false;
	// }

	$card_id = $_POST['card_id'];

	try {

		$subscriptions = $stripe->subscriptions->all([
			'limit' => 100,
			'customer' => $customer_id
		]);

		$sourceIsBeingUsed = false;

		foreach ($subscriptions->data as $subscription) {
			if ($subscription->default_payment_method === $card_id) {
				// IF THERE IS AN ERROR, CATCH IT AND SEND IT TO BROWSER
				$sourceIsBeingUsed = true;
			}
		}

		if ($sourceIsBeingUsed) {
			$return_data["success"] = false;

			wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_saved_card'] . '?success=' . $return_data["success"] . '&cardbeingused=1');
			exit;

			die();
		} else {
			$stripe->customers->deleteSource(
				$customer_id,
				$card_id,
				[]
			);

			$return_data["success"] = true;

			wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_saved_card'] . '?success=' . $return_data["success"] . '&deletecard=1&customer_id=' . $customer_id . '');
			exit;

			die();
		}
	} catch (\Stripe\Exception\CardException $e) {
		$body = $e->getJsonBody();
		$err  = $body['error'];
		$error = $err['message'];
	}
}








/**
 * ACTIVATE CARD ACTION FLOW
 */
function stripe_activate_card()
{
	global $stripe_secret_key;
	$stripe = new \Stripe\StripeClient(
		$stripe_secret_key
	);

	// global $stripe_is_TEST_mode;
	// global $stripe_is_LIVE_mode;
	global $customer_id;

	//to retrive the API keys from admin page
	global $doenanova_options;

	//$current_url = $_POST['current_url'];

	$return_data = array();
	$return_data["success"] = false;

	// if (get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true) && isset($stripe_is_LIVE_mode)) {
	// 	$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true);
	// } else if (get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true) && isset($stripe_is_TEST_mode)) {
	// 	$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true);
	// } else {
	// 	$customer_id = false;
	// }

	$card_id = $_POST['card_id'];

	try {

		$stripe->customers->update(
			$customer_id,
			['default_source' => $card_id,]
		);

		$return_data["success"] = true;

		wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_saved_card'] . '?success=' . $return_data["success"] . '&activatecard=1&customer_id=' . $customer_id . '');
		exit;

		die();
	} catch (\Stripe\Exception\CardException $e) {
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
	$stripe = new \Stripe\StripeClient(
		$stripe_secret_key
	);

	// global $stripe_is_TEST_mode;
	// global $stripe_is_LIVE_mode;

	global $customer_id;

	//to retrive the API keys from admin page
	global $doenanova_options;

	// $current_url = $_POST['current_url'];

	$return_data = array();
	$return_data["success"] = false;

	// if (get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true) && isset($stripe_is_LIVE_mode)) {
	// 	$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true);
	// } else if (get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true) && isset($stripe_is_TEST_mode)) {
	// 	$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true);
	// } else {
	// 	$customer_id = false;
	// }

	$subscriptionId = $_POST['subscription_id'];

	try {

		$stripe->subscriptions->cancel(
			$subscriptionId,
			[]
		);

		$return_data["success"] = true;

		wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_recurring_donations'] . '?success=' . $return_data["success"] . '&cancelsubs=1&customer_id=' . $customer_id . '');
		exit;

		die();
	} catch (\Stripe\Exception\CardException $e) {
		$body = $e->getJsonBody();
		$err  = $body['error'];
		$error = $err['message'];
	}
}




/**
 * CHANGE CARD FOR SUBSCRIPTION ACTION FLOW
 */
function stripe_change_card_for_subscription()
{
	global $stripe_secret_key;
	$stripe = new \Stripe\StripeClient(
		$stripe_secret_key
	);

	// global $stripe_is_TEST_mode;
	// global $stripe_is_LIVE_mode;

	global $customer_id;

	//to retrive the API keys from admin page
	global $doenanova_options;

	//$current_url = $_POST['current_url'];

	$return_data = array();
	$return_data["success"] = false;

	// if (get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true) && isset($stripe_is_LIVE_mode)) {
	// 	$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_LIVE_id', true);
	// } else if (get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true) && isset($stripe_is_TEST_mode)) {
	// 	$customer_id = get_user_meta(get_current_user_id(), '_stripe_customer_TEST_id', true);
	// } else {
	// 	$customer_id = false;
	// }

	$subscriptionId = $_POST['subscription_id'];
	$subscriptionNewCardId = $_POST['subscription_new_card'];


	try {
		$stripe->subscriptions->update(
			$subscriptionId,
			["default_payment_method" => $subscriptionNewCardId,]
		);

		$return_data["success"] = true;

		wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_recurring_donations'] . '?success=' . $return_data["success"] . '&changecard=1&customer_id=' . $customer_id . '');
		exit;

		die();
	} catch (\Stripe\Exception\CardException $e) {
		$body = $e->getJsonBody();
		$err  = $body['error'];
		$error = $err['message'];
	}
}
