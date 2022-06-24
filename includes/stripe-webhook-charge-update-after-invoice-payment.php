<?php

global $stripe_secret_key;
global $stripe_is_TEST_mode;
global $stripe_is_LIVE_mode;

//to retrive the API keys from admin page
global $doenanova_options;

$stripe = new \Stripe\StripeClient(
	$stripe_secret_key
);


//Disable HTTP Cache
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

// Retrieve the request's body and parse it as JSON
$input = @file_get_contents("php://input");
$event_json = json_decode($input);



if ($event_json->type == "invoice.payment_succeeded") {

	$customerId = $event_json->data->object->customer;

	$chargeId = $event_json->data->object->charge;

	$finalidade = $event_json->data->object->lines->data[0]->metadata->finalidade;

	$recorrencia = $event_json->data->object->lines->data[0]->metadata->recorrencia;


	$cu = \Stripe\Customer::retrieve($customerId);

	$cuDesc = $cu->description;

	$ch = \Stripe\Charge::retrieve($chargeId);
	$ch->description = "Doação recorrente de " . $cuDesc;
	$ch->metadata->finalidade = $finalidade;
	$ch->metadata->recorrencia = $recorrencia;
	$ch->save();
}


http_response_code(200); // PHP 5.4 or greater


































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
		$customer = $stripe->customers->create([
			'email' => $user_email,
			'source' => $token,
			'description' => $user_name,
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



//RECURRING DONATIONS
try {
	// $product = $stripe->products->create([
	// 	'name' => $amount,
	// 	'metadata' => ['Purpose' => $purpose, 'Frequency' => $frequency,],
	// 	'description' => 'Doação recorrente',
	// ]);

	// $price = $stripe->prices->create([
	// 	'unit_amount' => $amount * 100,
	// 	'currency' => 'brl',
	// 	'recurring' => ['interval' => $frequency],
	// 	'product' => $product->id,
	// 	'metadata' => ['Purpose' => $purpose, 'Frequency' => $frequency,],
	// ]);

	// $subscription = $stripe->subscriptions->create([
	// 	'customer' => $customer_id,
	// 	'items' => [
	// 		['price' => $price->id],
	// 	],
	// 	'default_payment_method' => $source->data[0]->id,
	// 	'metadata' => ['Purpose' => $purpose, 'Frequency' => $frequency,],
	// ]);

	$invoiceId = $subscription->latest_invoice;

	$stripe->invoices->update(
		$invoiceId,
		['metadata' => ['Purpose' => $purpose, 'Frequency' => $frequency,]]
	);

	$invoiceObj = $stripe->invoices->retrieve(
		$invoiceId,
		[]
	);

	$stripe->charges->update(
		$invoiceObj->charge,
		['metadata' => ['Purpose' => $purpose, 'Frequency' => $frequency,]]
	);


	// $return_data["success"] = true;

	// wp_redirect(home_url() . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_result'] . '?success=' . $return_data["success"] . '&amount=' . $amount . '&user_email=' . $user_email . '&user_name=' . $user_name . '&customer_id=' . $customer_id . '');

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
