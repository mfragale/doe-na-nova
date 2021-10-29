<?php include(dirname(__FILE__) . '/doe-na-nova-nav.php'); ?>
<?php include(dirname(__FILE__) . '/parts/part-wrap-start.php'); ?>

<?php if (is_user_logged_in()) {

	global $stripe_publishable_key;
	global $stripe_secret_key;

	if ($stripe_publishable_key && $stripe_secret_key) { // IF STRIPE KEYS ARE SET BY USER IN WP ADMIN PLUGIN PAGE, THEN SHOW THE DONATION FORM

		include(dirname(__FILE__) . '/parts/part-header.php');

		if ($customer_id) {
			$customer = $stripe->customers->retrieve(
				$customer_id,
				[]
			);
			$customer_source = $customer->default_source;

			$latest_charge = $stripe->charges->all([
				'limit' => 1,
				'customer' => $customer_id
			]);

			$latest_charge_data = $latest_charge->data[0];
			$latest_charge_meta_purpose = $latest_charge_data->metadata->Purpose;
		} else {
			$customer_source = false;
			$latest_charge_meta_purpose = false;
		}


		//THIS IS IN CASE THE ADMIN USER DOESN'T REQUIRE THE OTHER USER TO BE LOGGED IN IN ORDER TO DONATE
		if (is_user_logged_in()) {
			$current_user_email = $current_user->user_email;
			$current_user_name = $current_user->user_firstname . ' ' . $current_user->user_lastname;
		} else {
			$current_user_email = 'oi';
			$current_user_name = 'Anonimous';
		}

?>



		<script src="https://js.stripe.com/v3/"></script>




		<!-- ********** 
			DONATION FORM 
			********** -->

		<form id="form-doar" class="mt-3 mb-5" action="<?php echo get_admin_url(); ?>admin-post.php" method="POST">

			<?php //THIS FIELD WILL DETERMINE WHAT ACTION THE FROM SHOULD DO - relating to doe-na-nova-form-doar.php and stripe-checkout.php 
			?>
			<input type='hidden' name='action' value='stripe_checkout' />
			<?php if (is_user_logged_in()) { ?>
				<input type="hidden" name="user_email" id="user_email" value="<?php echo $current_user_email; ?>">
			<?php } ?>
			<input type="hidden" name="user_name" id="user_name" value="<?php echo $current_user_name; ?>">
			<input type="hidden" name="stripe-pk" id="stripe-pk" value="<?= $stripe_publishable_key ?>">
			<input type="hidden" name="current_url" id="current_url" value="<?php echo get_permalink(); ?>">





			<!-- ********** AMOUNT ********** -->
			<div id="amount_wrap" class="input-group mb-2 d-flex justify-content-center">
				<span class="input-group-text"><?php echo doe_na_nova_currency_symbol(); ?></span>
				<span class="amount_width_container">
					<input type="number" name="amount" class="amount form-control" id="amount" placeholder="0" required autofocus autocomplete="off">
				</span>
				<span class="input-group-text">,00</span>
			</div>
			<!-- ********** AMOUNT ********** -->






			<div class="row mb-2 gx-2">
				<div class="col">




					<!-- ********** FREQUENCY ********** -->
					<div class="form-floating">
						<select class="form-select" id="frequency" name="frequency">
							<option value="one time" checked selected><?php _e('One time', 'doenanova'); ?></option>

							<?php if (is_user_logged_in()) { ?>
								<option value="week"><?php _e('Weekly', 'doenanova'); ?></option>
								<option value="month"><?php _e('Monthly', 'doenanova'); ?></option>
								<option value="year"><?php _e('Yearly', 'doenanova'); ?></option>
							<?php } ?>
						</select>
						<label for="frequency"><?php _e('Frequency', 'doenanova'); ?></label>
					</div>
					<!-- ********** FREQUENCY ********** -->




				</div><!-- <div class="col"> -->
				<div class="col">




					<!-- ********** PURPOSE ********** -->
					<div class="form-floating">
						<select class="form-select" id="purpose" name="purpose">
							<?php
							if (!empty($doenanova_options['donation_purposes'])) {
								$donation_purposes = $doenanova_options['donation_purposes'];
							} else {
								$donation_purposes = __('Tithes & Offerings', 'doenanova');
							}

							$donation_purposes_lines = explode("\n", $donation_purposes); // or use PHP PHP_EOL constant

							foreach ($donation_purposes_lines as $donation_purposes_line) { ?>

								<option <?php if ($latest_charge_meta_purpose == trim($donation_purposes_line)) {
											echo 'checked selected';
										} ?> value="<?php echo trim($donation_purposes_line); ?>"><?php echo trim($donation_purposes_line); ?></option>';

							<?php
							}
							?>
						</select>
						<label for="purpose"><?php _e('Purpose', 'doenanova'); ?></label>
					</div><!-- <div class="select"> -->
					<!-- ********** PURPOSE ********** -->




				</div><!-- <div class="col"> -->
			</div><!-- <div class="row"> -->







			<?php if ($customer_source) { ?>


				<!-- ********** PAYMENT METHOD - CARD LIST ********** -->
				<?php $card = $stripe->customers->retrieveSource(
					$customer_id,
					$customer_source,
					[]
				);
				?>

				<div class="card cartoes-salvos mb-2 text-light bg-dark">
					<div class="card-body d-flex align-items-center justify-content-between">
						<div class="cartoes-salvos-last-4">
							<i class="fab fa-cc-<?php echo strtolower($card->brand); ?>"></i> •••• •••• •••• <span><?php echo $card->last4; ?></span>
						</div><!-- <div class="cartoes-salvos-last-4"> -->

						<a href="<?php echo get_bloginfo('url') . '' . doenanova_app_slug() . '' . $doenanova_options['page_saved_card']; ?>" class="btn btn-sm btn-light load-on-click" title="<?php _e('Change payment method', 'doenanova'); ?>">
							<i class="fas fa-exchange-alt"></i>
						</a>
					</div><!-- <div class="cartoes-salvos"> -->
				</div>
				<!-- ********** PAYMENT METHOD - CARD LIST ********** -->



			<?php } else { ?>



				<!-- ********** PAYMENT METHOD - CARD FIELDS ********** -->

				<?php if (!is_user_logged_in()) { //THIS IS IN CASE THE ADMIN USER DOESN'T REQUIRE THE OTHER USER TO BE LOGGED IN IN ORDER TO DONATE 
				?>
					<div class="field">
						<input type="email" class="doenanova-input" name="user_email" id="user_email" placeholder="<?php _e('Email', 'doenanova'); ?>" autocomplete="cc-email" required>
					</div>
				<?php } ?>

				<div class="form-floating mb-2">
					<input for="name" type="text" class="form-control" id="name" autocomplete="cc-name" required placeholder="<?php _e('Name on card', 'doenanova'); ?>" value="<?php echo $current_user_name; ?>">
					<label for="name"><?php _e('Name on card', 'doenanova'); ?></label>
				</div>

				<div class="form-floating mb-2">
					<div id="card" class="form-control">
						<!-- Stripe Card Element -->
					</div>
					<label for="card"><?php _e('Card info', 'doenanova'); ?></label>
				</div>
				<!-- ********** PAYMENT METHOD - CARD FIELDS ********** -->



			<?php } ?>





			<!-- ********** SUBMIT ********** -->
			<div class="d-grid gap-2 col mx-auto">
				<button id="payment-submit" class="btn btn-primary btn-block"><?php _e('Donate', 'doenanova'); ?></button>
			</div>
			<!-- ********** SUBMIT ********** -->




		</form>
		<!-- ********** 
			DONATION FORM 
		********** -->










		<!-- ********** TEST MODE TAG & REPORT ********** -->
		<?php //include(dirname(__FILE__) . '/parts/part-test-tag-and-report.php'); 
		?>
		<!-- ********** TEST MODE TAG & REPORT ********** -->








<?php //if ($stripe_publishable_key && $stripe_secret_key)
	} else { // IF STRIPE KEYS ARE NOT SET BY USER IN WP ADMIN PLUGIN PAGE, THEN DON'T SHOW THE DONATION FORM AND SHOW BELOW

		include(dirname(__FILE__) . '/parts/part-no-stripe-api-keys.php');
	} //if ($stripe_publishable_key && $stripe_secret_key)  



} else { //if (is_user_logged_in())

	include(dirname(__FILE__) . '/parts/part-login-and-register.php');
} //if (is_user_logged_in()) 
?>



<?php include(dirname(__FILE__) . '/parts/part-wrap-end.php'); ?>