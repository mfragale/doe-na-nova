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

$currency = $doenanova_options['currency'];
$platformAccountId = 'acct_17wccsEYlIfdDhnn';

$stripe = new \Stripe\StripeClient(
    $stripe_secret_key
);


//Disable HTTP Cache
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

// Retrieve the request's body and parse it as JSON
//$input = @file_get_contents("php://input");
//$event_json = json_decode($input);

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = 'whsec_178fb16924187737e506af0ac7b934b2fcb4446a9b6dbcf7a780f741057934bf';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sig_header,
        $endpoint_secret
    );
} catch (\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->type) {
    case 'invoice.payment_succeeded':

        $paymentIntentId = $event->data->object->payment_intent;
        $chargeId = $event->data->object->charge;
        $amount = $event->data->object->lines->data[0]->amount;
        $purpose = $event->data->object->lines->data[0]->plan->metadata->Purpose;
        $frequency = $event->data->object->lines->data[0]->plan->metadata->Frequency;
        $description = 'Doação recorrente - ciclo contínuo';

        $purposeObj = $stripe->accounts->retrieve(
            $purpose,
            []
        );

        // $stripe->paymentIntents->update(
        //     $paymentIntentId,
        //     [
        //         'metadata' => [
        //             'Purpose' => $purposeObj->settings->dashboard->display_name,
        //             'Frequency' => $frequency,
        //         ],
        //         'description' => $description
        //     ]
        // );

        // // If Stripe Connected Account ID (Purpose) is not equal to Main Platform Account ID, then transfer the charge to Stripe Connected Account.
        // if ($purpose != $platformAccountId) {
        //     $transfer = $stripe->transfers->create([
        //         "amount" => $amount * 100,
        //         "currency" => $currency,
        //         "source_transaction" => $chargeId,
        //         "destination" => $purpose,
        //     ]);
        // }

    default:
        echo 'Received unknown event type ' . $event->type;
}



http_response_code(200); // PHP 5.4 or greater

?>


<h1>Stripe Webhook Page</h1>
<p><?php //echo $chargeId 
    ?></p>
<p><?php //echo $purpose 
    ?></p>
<p><?php //echo $frequency 
    ?></p>
<p><?php //echo $description 
    ?></p>