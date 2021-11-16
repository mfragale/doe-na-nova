<?php include(dirname(__FILE__) . '/doe-na-nova-nav.php'); ?>
<?php include(dirname(__FILE__) . '/parts/part-wrap-start.php'); ?>

<?php if (is_user_logged_in()) {

	global $stripe_publishable_key;
	global $stripe_secret_key;

	if ($stripe_publishable_key && $stripe_secret_key) { // IF STRIPE KEYS ARE SET BY USER IN WP ADMIN PLUGIN PAGE, THEN SHOW THE DONATION FORM

		include(dirname(__FILE__) . '/parts/part-header.php');

		if ($customer_id) {
			$charges = $stripe->charges->all([
				'limit' => 10,
				"customer" => $customer_id,
			]);
		}

		setlocale(LC_TIME, get_locale());
?>


		<div class="table-responsive mt-3" id="transacoes">
			<table class="table align-middle">
				<tbody>

					<?php if (!empty($charges->data)) { ?>

						<?php foreach ($charges->data as $charge) { ?>

							<?php
							if ($charge->status == 'succeeded') {
								$badge = 'text-success';
								$icon = 'check-circle';
								$status = 'Succeeded';
							} else if ($charge->status == 'pending') {
								$badge = 'text-warning';
								$icon = 'exclamation-circle';
								$status = 'Pending';
							} else if ($charge->status == 'failed') {
								$badge = 'text-danger';
								$icon = 'times-circle';
								$status = 'Failed text-muted';
							}


							if ($charge->invoice) { // charge is from a subscription
								$invoice = $stripe->invoices->retrieve(
									$charge->invoice,
									[]
								);
								$charge_purpose = $invoice->lines->data[0]->metadata->Purpose;
								$charge_frequency = $invoice->lines->data[0]->metadata->Frequency;
							} else { //charge is from a non recurrent donation
								$charge_purpose = $charge->metadata->Purpose;
								$charge_frequency = $charge->metadata->Frequency;
							}

							if ($charge_frequency == 'month') {
								$charge_frequency = __('Monthly', 'doenanova');
							} else if ($charge_frequency == 'week') {
								$charge_frequency = __('Weekly', 'doenanova');
							} else if ($charge_frequency == 'year') {
								$charge_frequency = __('Yearly', 'doenanova');
							} else {
								$charge_frequency = '';
							}

							?>

							<tr class="<?php echo $status; ?> <?php echo $charge->id ?>">
								<td>
									<div><?php echo $charge_purpose; ?></div>
									<div><small><?php echo $charge_frequency; ?></small></div>
								</td>
								<td><i class="fab fa-cc-<?php echo strtolower($charge->source->brand); ?>"></i> <?php echo $charge->source->last4; ?></td>
								<td><?php echo date('d/m/y', $charge->created); ?></td>
								<td class="text-end <?php echo $badge; ?>"> <?php echo doe_na_nova_currency_symbol(); ?><?php echo $charge->amount / 100; ?>.00 </td>
							</tr>


						<?php } //foreach ($charges->data as $charge) 
						?>


					<?php } else { //If no charges were made 
					?>

						<h3 class="empty-notification"><?php _e('No transactions.', 'doenanova'); ?></h3>
						<h5 class="empty-notification"><?php _e("Once you make a donation you'll see your transactions information here.", 'doenanova'); ?></h5>

					<?php } // If no charges were made 
					?>

				</tbody>
			</table>
		</div>


		<?php if (!empty($charges->data)) {
			if ($charges->has_more) { ?>

				<?php
				/**
				 * AJAX loadmore_recent_transactions
				 */
				?>
				<div id="loadmore_recent_transactions-btn_container">

					<!-- Fields to send to AJAX -->
					<input type="hidden" id="last_charge" value="<?php echo $charge->id ?>" />

					<script src="https://js.stripe.com/v3/"></script>

					<!-- Fail message -->
					<div id="loadmore_recent_transactions-fail-message" class="alert alert-danger loadmore_recent_transactions-message"><?php _e('Transactions could not be loaded.', 'doenanova'); ?></div>

					<!-- Load more Button -->
					<div class="d-grid gap-2">
						<a id="loadmore_recent_transactions-btn" href="#" class="btn btn-primary"><?php _e('Show more', 'doenanova'); ?></a>
					</div>

				</div>


		<?php }
		} //if ($charges->has_more) 
		?>








<?php //if ($stripe_publishable_key && $stripe_secret_key)
	} else { // IF STRIPE KEYS ARE NOT SET BY USER IN WP ADMIN PLUGIN PAGE, THEN DON'T SHOW THE DONATION FORM AND SHOW BELOW

		include(dirname(__FILE__) . '/parts/part-no-stripe-api-keys.php');
	} //if ($stripe_publishable_key && $stripe_secret_key)  



} else { //if (is_user_logged_in())

	include(dirname(__FILE__) . '/parts/part-login-and-register.php');
} //if (is_user_logged_in()) 
?>


<?php include(dirname(__FILE__) . '/parts/part-wrap-end.php'); ?>