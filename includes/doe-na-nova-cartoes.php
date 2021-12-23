<?php include(dirname(__FILE__) . '/doe-na-nova-nav.php'); ?>
<?php include(dirname(__FILE__) . '/parts/part-wrap-start.php'); ?>


<?php if (is_user_logged_in()) {

	global $stripe_publishable_key;
	global $stripe_secret_key;

	if ($stripe_publishable_key && $stripe_secret_key) { // IF STRIPE KEYS ARE SET BY USER IN WP ADMIN PLUGIN PAGE, THEN SHOW THE DONATION FORM

		include(dirname(__FILE__) . '/parts/part-header.php');

		if ($customer_id) {
			$cardList = $stripe->customers->allSources(
				$customer_id,
				['object' => 'card']
			);

			if (!$cardList->data) {
				$cardList = false;
			}
		}
?>



		<script src="https://js.stripe.com/v3/"></script>



		<div class="accordion" id="cartoes">


			<?php if (isset($_GET['success'])) {
				if (isset($_GET["deletecard"])) { ?>
					<div class="alert alert-success">
						<i class="fas fa-check"></i> <?php _e('Card successfully deleted', 'doenanova'); ?>
					</div>
			<?php }
			} ?>



			<?php if (!empty($cardList)) { ?>

				<?php foreach ($cardList->data as $card) { ?>


					<div class="accordion-item" id="">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#subs-<?php echo $card->id; ?>-dropdown" aria-expanded="true" aria-controls="subs-<?php echo $card->id; ?>-dropdown">
							<div class="col">
								<div><small><i class="fab fa-2x fa-cc-<?php echo strtolower($card->brand); ?>"></i></small> •••• •••• •••• <?php echo $card->last4; ?></div>
							</div>
							<div class="col text-end me-3">
								<?php echo $card->funding; ?>
							</div>
						</button>

						<div id="subs-<?php echo $card->id; ?>-dropdown" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
							<div class="accordion-body">
								<form id="delete_card" action="<?php echo get_admin_url(); ?>admin-post.php" method="POST">
									<input type='hidden' name='action' value='stripe_delete_card' />
									<input type="hidden" name='card_id' value='<?php echo $card->id; ?>' />
									<input type="hidden" name="current_url" id="current_url" value="<?php echo get_permalink(); ?>">
									<button class="btn btn-xs btn-danger float-end load-on-click" title="<?php _e('Delete this card', 'doenanova'); ?>"><i class="fas fa-trash-alt"></i></button>
								</form>

								<table class="table">
									<tbody>
										<tr>
											<th scope="row"><?php _e('Card name', 'doenanova'); ?></th>
											<td><?php echo $card->name; ?></td>
										</tr>
										<tr>
											<th scope="row"><?php _e('Expire date', 'doenanova'); ?></th>
											<td><?php echo $card->exp_month; ?>/<?php echo $card->exp_year; ?></td>
										</tr>
									</tbody>
								</table>

							</div>
						</div>
					</div>




				<?php } ?>

			<?php } else { ?>

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

<?php include(dirname(__FILE__) . '/parts/part-wrap-end.php'); ?>