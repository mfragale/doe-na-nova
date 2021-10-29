<?php include(dirname(__FILE__) . '/doe-na-nova-nav.php'); ?>
<?php include(dirname(__FILE__) . '/parts/part-wrap-start.php'); ?>

<?php
global $stripe_publishable_key;
global $stripe_secret_key;

if ($stripe_publishable_key && $stripe_secret_key) { // IF STRIPE KEYS ARE SET BY USER IN WP ADMIN PLUGIN PAGE, THEN SHOW THE DONATION FORM

	include(dirname(__FILE__) . '/parts/part-header.php');

	if (isset($_GET['customer_id'])) {
		$customer_id = $_GET["customer_id"];
	}

	if ($customer_id) {
		$charges = $stripe->charges->all([
			'limit' => 1,
			"customer" => $customer_id,
		]);
	}
?>


	<?php if (isset($_GET["success"])) { ?>

		<?php foreach ($charges->data as $charge) { ?>

			<div id="resultado-doacao" class="donation-successful">

				<div class="resultado-doacao-header">
					<img src="<?php echo plugin_dir_url(__FILE__); ?>img/success-check.gif" alt="success-check" width="100" height="101" />
					<h3><?php _e('Thank you', 'doenanova'); ?> <?php echo get_user_meta(get_current_user_id(), 'first_name', true); ?>!</h3>
					<h5><?php _e('Your donation was successfull.', 'doenanova'); ?></h5>
				</div>

				<table class="table">
					<tbody>
						<tr>
							<th scope="row"><?php _e('Amount', 'doenanova'); ?></th>
							<td><?php echo doe_na_nova_currency_symbol(); ?><?php echo $charge->amount / 100; ?>,00</td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Method', 'doenanova'); ?></th>
							<td><i class="fab fa-cc-<?php echo strtolower($charge->source->brand); ?>"></i> <?php echo $charge->source->last4; ?></td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Date', 'doenanova'); ?></th>
							<td><?php echo date('d/m/Y', $charge->created); ?></td>
						</tr>
					</tbody>
				</table>

				<div class="resultado-doacao-footer">

					<small><?php _e('An email has been sent to you with all this information. If you have any questions, contact us at', 'doenanova'); ?> <a href='mailto:<?php echo get_bloginfo('admin_email'); ?>'><?php echo get_bloginfo('admin_email'); ?></a></small>

				</div>

			</div><!-- #resultado-doacao -->

		<?php } ?>



	<?php } else { ?>



		<div id="resultado-doacao" class="donation-declined">

			<div class="resultado-doacao-header">

				<img src="<?php echo plugin_dir_url(__FILE__); ?>img/decline-cross.jpg" alt="decline-cross" width="264" height="266" />
				<h3><?php _e('Dear,', 'doenanova'); ?> <?php echo get_user_meta(get_current_user_id(), 'first_name', true); ?></h3>
				<h5><?php _e("Your donation wasn't successfull. Here's why:", "doenanova"); ?></h5>

				<div class="" role="alert">

				</div>

				<div class="alert alert-danger">
					<?php if (isset($_GET["message"])) {
						echo $_GET["message"];
					} else {
						_e('No donation was made', 'doenanova');
					}
					?>
				</div>

			</div>



			<div class="resultado-doacao-footer">

				<small><?php _e("If you're unsure why this happened, please try again.", "doenanova"); ?></small>

				<div class="resultado-doacao-footer mt-3">

					<div class="row">
						<div class="col">
							<a href="<?php echo get_bloginfo('url') . '' . doenanova_app_slug() . '' . $doenanova_options['page_recent_transactions']; ?>" class="btn btn-sm btn-light load-on-click"><?php _e('See transactions', 'doenanova'); ?></a>
						</div><!-- <div class="doenanova-column"> -->
						<div class="col">
							<a href="<?php echo get_bloginfo('url') . '' . doenanova_app_slug() . '' . $doenanova_options['page_donation_form']; ?>" class="btn btn-sm btn-primary load-on-click"><?php _e("Try to donate again", "doenanova"); ?></a>
						</div><!-- <div class="doenanova-column"> -->
					</div><!-- <div class="doenanova-columns"> -->

				</div>




			</div>

		</div><!-- #resultado-doacao -->


	<?php } ?>





<?php //if ($stripe_publishable_key && $stripe_secret_key)
} else { // IF STRIPE KEYS ARE NOT SET BY USER IN WP ADMIN PLUGIN PAGE, THEN DON'T SHOW THE DONATION FORM AND SHOW BELOW

	include(dirname(__FILE__) . '/parts/part-no-stripe-api-keys.php');
} //if ($stripe_publishable_key && $stripe_secret_key) 
?>


<?php include(dirname(__FILE__) . '/parts/part-wrap-end.php'); ?>