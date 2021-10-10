<div id="doenanova-wrap" <?php doenanova_wrap_fadein(); ?>>

	<?php include(dirname(__FILE__) . '/doe-na-nova-header.php'); ?>

	<?php if (is_user_logged_in()) {

		global $stripe_publishable_key;
		global $stripe_secret_key;

		if ($stripe_publishable_key && $stripe_secret_key) { // IF STRIPE KEYS ARE SET BY USER IN WP ADMIN PLUGIN PAGE, THEN SHOW THE DONATION FORM

			include(dirname(__FILE__) . '/parts/part-header.php');

			if ($customer_id) {
				$cardList = \Stripe\Customer::retrieve($customer_id)->sources->all(array(
					'object' => 'card'
				));

				if (!$cardList->data) {
					$cardList = false;
				}
			}
	?>



			<script src="https://js.stripe.com/v3/"></script>



			<div id="cartoes">


				<?php if (isset($_GET['success'])) {
					if (isset($_GET["deletecard"])) { ?>
						<div class="notification is-success">
							<i class="fas fa-check"></i> <?php _e('Card successfully deleted', 'doenanova'); ?>
						</div>
				<?php }
				} ?>



				<?php if (!empty($cardList)) { ?>

					<?php foreach ($cardList->data as $card) { ?>


						<div class="card">
							<div class="upper">
								<div class="d-flex justify-content-between">
									<h6><?php echo $card->funding; ?></h6>
									<h4><i class="fab fa-2x fa-cc-<?php echo strtolower($card->brand); ?>"></i></h4>
								</div>
							</div>
							<div class="lower">
								<h5><?php echo $card->name; ?></h5>
								<div class="d-flex justify-content-between">
									<div>•••• •••• •••• <?php echo $card->last4; ?></div>
									<div><?php echo $card->exp_month; ?>/<?php echo $card->exp_year; ?></div>
								</div>

							</div>
						</div>


						<form id="delete_card" action="<?php echo get_admin_url(); ?>admin-post.php" method="POST">

							<input type='hidden' name='action' value='stripe_delete_card' />
							<input type="hidden" name='card_id' value='<?php echo $card->id; ?>' />
							<input type="hidden" name="current_url" id="current_url" value="<?php echo get_permalink(); ?>">

							<button type="button" class="btn btn-sm btn-danger load-on-click" title="<?php _e('Delete this card', 'doenanova'); ?>"><i class="fas fa-trash-alt"></i> <?php _e(' Delete this card', 'doenanova'); ?></button>

						</form>


					<?php } ?>

				<?php } else { ?>

					<div class="credit-card is-clearfix">

						<div class="cc-funding"></div>

						<div class="cc-brand"></div>

						<div class="cc-number">•••• •••• •••• ••••</div>

						<div class="cc-name"><small><?php _e('Name', 'doenanova'); ?>:</small><br />----------</div>

						<div class="cc-exp"><small><?php _e('Expire date', 'doenanova'); ?>:</small><br />--/--</div>

					</div>


					<h3 class="empty-notification"><?php _e('No saved card.', 'doenanova'); ?></h3>
					<h5 class="empty-notification"><?php _e("Once you make a donation you'll see your card information here.", 'doenanova'); ?></h5>
					<!--
			  <br/>
			  <p><a href="<?php echo get_bloginfo('url') . '/' . doenanova_app_slug() . '' . $doenanova_options['page_donation_form']; ?>" class="doenanova-btn doenanova-btn-block load-on-click"><?php _e("Make a donation", "doenanova"); ?></a></p>
-->



				<?php } ?>


			</div><!-- #cartoes-salvos -->








	<?php //if ($stripe_publishable_key && $stripe_secret_key)
		} else { // IF STRIPE KEYS ARE NOT SET BY USER IN WP ADMIN PLUGIN PAGE, THEN DON'T SHOW THE DONATION FORM AND SHOW BELOW

			include(dirname(__FILE__) . '/parts/part-no-stripe-api-keys.php');
		} //if ($stripe_publishable_key && $stripe_secret_key) 



	} else { //if (is_user_logged_in())

		include(dirname(__FILE__) . '/parts/part-login-and-register.php');
	} //if (is_user_logged_in()) 
	?>

</div><!-- id="doenanova-wrap" -->