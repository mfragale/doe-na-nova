<?php include(dirname(__FILE__) . '/doe-na-nova-nav.php'); ?>
<?php include(dirname(__FILE__) . '/parts/part-wrap-start.php'); ?>


<?php if (is_user_logged_in()) {

	global $stripe_publishable_key;
	global $stripe_secret_key;

	if ($stripe_publishable_key && $stripe_secret_key) { // IF STRIPE KEYS ARE SET BY USER IN WP ADMIN PLUGIN PAGE, THEN SHOW THE DONATION FORM

		include(dirname(__FILE__) . '/parts/part-header.php');

		if ($customer_id) {
			$subscriptions = $stripe->subscriptions->all([
				"customer" => $customer_id,
				"limit" => 10,
				"status" => 'active'
			]);

			$customer = $stripe->customers->retrieve(
				$customer_id,
				[]
			);

			if (isset($customer->default_source)) {
				$source = $stripe->customers->retrieveSource(
					$customer_id,
					$customer->default_source,
					[]
				);
				$customer_cardBrand = strtolower($source->brand);
				$customer_cardLast4 = $source->last4;
			} else {
				$customer_cardBrand = false;
				$customer_cardLast4 = __('No saved card.', 'doenanova');
			}
		} else {
			$subscriptions = false;
		}

		setlocale(LC_TIME, get_locale());
?>




		<div class="accordion" id="recorrentes">

			<?php if (isset($_GET['success'])) {
				if (isset($_GET["cancelsubs"])) { ?>
					<div class="alert alert-success">
						<i class="fas fa-check"></i> <?php _e('Recurring donation deleted', 'doenanova'); ?>
					</div>
			<?php }
			} ?>


			<?php if (!empty($subscriptions->data)) { ?>

				<?php foreach ($subscriptions->data as $subscription) { ?>

					<?php
					if ($subscription->status == 'active') {
						$badge = 'is-success';
						$icon = 'check-circle';
						$status = 'Active';
					}

					if ($subscription->plan->interval == 'month') {
						$subscription_interval = __('Monthly', 'doenanova');
					} else if ($subscription->plan->interval == 'week') {
						$subscription_interval = __('Weekly', 'doenanova');
					} else if ($subscription->plan->interval == 'year') {
						$subscription_interval = __('Yearly', 'doenanova');
					}
					?>

					<div class="accordion-item <?php echo $status; ?>" id="subs-<?php echo $subscription->id; ?>">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subs-<?php echo $subscription->id; ?>-dropdown" aria-expanded="true" aria-controls="subs-<?php echo $subscription->id; ?>-dropdown">
							<div class="col">
								<div><?php echo $subscription->metadata->Purpose; ?></div>
								<div><small><?php echo $subscription_interval; ?></small></div>
							</div>
							<div class="col text-end me-3">
								<?php echo doe_na_nova_currency_symbol(); ?><?php echo $subscription->plan->amount / 100; ?>.00
							</div>
						</button>

						<div id="subs-<?php echo $subscription->id; ?>-dropdown" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								<form action="<?php echo get_admin_url(); ?>admin-post.php" method="POST">
									<input type='hidden' name='action' value='stripe_cancel_subscription' />
									<input type="hidden" name='subscription_id' value='<?php echo $subscription->id; ?>' />
									<input type="hidden" name="current_url" id="current_url" value="<?php echo get_permalink(); ?>">
									<button class="btn btn-xs btn-danger float-end load-on-click cancel_subscription_btn" title="<?php _e('Delete this recurring donation', 'doenanova'); ?>"><i class="fas fa-trash-alt"></i></button>
								</form>

								<table class="table">
									<tbody>
										<tr>
											<th scope="row"><?php _e('Created on', 'doenanova'); ?></th>
											<td><?php echo strftime("%a, %d/%m/%y", $subscription->created); ?></td>
										</tr>
										<tr>
											<th scope="row"><?php _e('Frequency', 'doenanova'); ?></th>
											<td><?php echo $subscription_interval; ?></td>
										</tr>
										<tr>
											<th scope="row"><?php _e('Next billing', 'doenanova'); ?></th>
											<td><?php echo strftime("%a, %d/%m/%y", $subscription->current_period_end); ?></td>
										</tr>
										<tr>
											<th scope="row"><?php _e('Amount', 'doenanova'); ?></th>
											<td><?php echo doe_na_nova_currency_symbol(); ?><?php echo $subscription->plan->amount / 100; ?>,00</td>
										</tr>
										<tr>
											<th scope="row"><?php _e('Payment method', 'doenanova'); ?></th>
											<td><i class="fab fa-cc-<?php echo $customer_cardBrand; ?>"></i> <?php echo $customer_cardLast4; ?></td>
										</tr>
										<tr>
											<th scope="row"><?php _e('Purpose', 'doenanova'); ?></th>
											<td><?php echo $subscription->metadata->Purpose; ?></td>
										</tr>
										<tr>
											<th scope="row"><?php _e('Status', 'doenanova'); ?></th>
											<td><span class="<?php echo $badge; ?> tag"><i class="fas fa-xs fa-<?php echo $icon; ?>"></i> <?php _e('Active', 'doenanova'); ?></span></td>
										</tr>
									</tbody>
								</table>

							</div>
						</div>
					</div>


				<?php } //foreach ($subscriptions->data as $subscription) 
				?>

			<?php } else { ?>


				<h3 class="empty-notification"><?php _e('No recurring donations.', 'doenanova'); ?></h3>
				<h5 class="empty-notification"><?php _e("Once you make a weekly, monthly or yearly donation you'll see their information here.", 'doenanova'); ?></h5>


			<?php } ?>


		</div>


		<?php if (!empty($subscriptions->data)) {
			if ($subscriptions->has_more) { ?>

				<?php
				/**
				 * AJAX loadmore_recurring_donations
				 */
				?>
				<div id="loadmore_recurring_donations-btn_container">

					<!-- Fields to send to AJAX -->
					<input type="hidden" id="last_subscription" value="<?php echo $subscription->id ?>" />

					<script src="https://js.stripe.com/v3/"></script>

					<!-- Fail message -->
					<div id="loadmore_recurring_donations-fail-message" class="alert alert-danger loadmore_recurring_donations-message"><?php _e('Recurring donations could not be loaded.', 'doenanova'); ?></div>

					<!-- Load more Button -->
					<div class="d-grid gap-2">
						<a id="loadmore_recurring_donations-btn" href="#" class="btn btn-primary"><?php _e('Show more', 'doenanova'); ?></a>
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