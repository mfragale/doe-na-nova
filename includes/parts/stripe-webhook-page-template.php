<?php

/**
 * A Stripe webhook to update a charge from a recurent payment on listen to "invoice.payment_succeeded" webhook.
 */

//Stripe
include_once dirname(__FILE__) . '/stripe-setup.php';
include_once($path_to_stripe_lib . 'init.php');

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

    try {
        $chargeId = $event_json->data->object->charge;

        $purpose = $event_json->data->object->lines->data[0]->metadata->Purpose;

        $frequency = $event_json->data->object->lines->data[0]->metadata->Frequency;

        $stripe->charges->update(
            $chargeId,
            [
                'metadata' => [
                    'Purpose' => $purpose,
                    'Frequency' => $frequency,
                ],
                'description' => 'Doação recorrente - ciclo contínuo'
            ]
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


http_response_code(200); // PHP 5.4 or greater

?>


<h1>Stripe Webhook Page</h1>