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
			$customer = $stripe->customers->retrieve(
				$customer_id,
				[]
			);
		}

		$current_user_email = $current_user->user_email;
		$current_user_name = $current_user->user_firstname . ' ' . $current_user->user_lastname;

?>



		<script src="https://js.stripe.com/v3/"></script>


		<?php
		if (isset($_GET['success'])) {
			if (isset($_GET["deletecard"])) { ?>
				<div class="alert alert-success"><i class="fas fa-check"></i> <?php _e('Card successfully deleted.', 'doenanova'); ?></div>
		<?php }
		} ?>

		<?php if (isset($_GET['cardbeingused'])) { ?>
			<div class="alert alert-danger"><i class="fas fa-times-circle"></i> <?php _e('This card could not be deleted because it is being used for one of your subscriptions.', 'doenanova'); ?> </div>
		<?php } ?>

		<?php if (isset($_GET['addcard'])) { ?>
			<div class="alert alert-success"><i class="fas fa-check"></i> <?php _e('Card successfully added.', 'doenanova'); ?></div>
		<?php } ?>

		<?php if (isset($_GET['activatecard'])) { ?>
			<div class="alert alert-success"><i class="fas fa-check"></i> <?php _e('Card successfully activated.', 'doenanova'); ?></div>
		<?php }


		if (isset($_POST['action']) && !empty($_POST['action'])) {
			echo json_encode(array("blablabla" => $variable));
		}


		?>


		<div class="accordion" id="cartoes">


			<?php if (!empty($cardList)) { ?>

				<?php foreach ($cardList->data as $card) { ?>


					<div class="accordion-item" id="card-<?php echo $card->id; ?>">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#card-<?php echo $card->id; ?>-dropdown" aria-expanded="true" aria-controls="card-<?php echo $card->id; ?>-dropdown">
							<div class="col">
								<i class="fab fa-cc-<?php echo strtolower($card->brand); ?>"></i>
								<?php echo $card->last4; ?>
								<span class="badge bg-light text-primary"><?php echo strtoupper($card->funding); ?></span>
								<?php if ($customer->default_source == $card->id) { ?><span class="badge bg-success"><?php _e('Active', 'doenanova'); ?></span><?php } ?>
							</div>
						</button>

						<div id="card-<?php echo $card->id; ?>-dropdown" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
							<div class="accordion-body p-0">

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
										<tr class="table-light">
											<th scope="row"><?php _e('Actions', 'doenanova'); ?></th>
											<td>

												<!-- Button trigger modal -->
												<?php if ($customer->default_source !== $card->id) { ?>
													<button type="button" class="btn btn-sm btn-secondary me-1" data-bs-toggle="modal" data-bs-target="#<?php echo $card->id; ?>-modal-activate" title="<?php _e('Activate this card', 'doenanova'); ?>">
														<?php _e('Use this card', 'doenanova'); ?>
													</button>
												<?php } ?>
												<!-- Button trigger modal -->
												<button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#<?php echo $card->id; ?>-modal" title="<?php _e('Delete this card', 'doenanova'); ?>">
													<i class="fas fa-trash-alt"></i>
												</button>

											</td>
										</tr>
									</tbody>
								</table>


								<!-- Modal DELETE CARD -->
								<div class="modal fade" id="<?php echo $card->id; ?>-modal" tabindex="-1" aria-labelledby="<?php echo $card->id; ?>-modalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="<?php echo $card->id; ?>-modalLabel"><?php _e('Delete card', 'doenanova'); ?></h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												Tem certeza que deseja deletar o cartão <i class="fab fa-cc-<?php echo strtolower($card->brand); ?>"></i> final <?php echo $card->last4; ?>?
											</div>
											<div class="modal-footer">
												<form id="delete_card" action="<?php echo get_admin_url(); ?>admin-post.php" method="POST">
													<input type='hidden' name='action' value='stripe_delete_card' />
													<input type="hidden" name='card_id' value='<?php echo $card->id; ?>' />
													<input type="hidden" name="current_url" id="current_url" value="<?php echo get_permalink(); ?>">
													<button type="button" class="btn btn-danger load-on-click"><?php _e('Yes, delete please', 'doenanova'); ?></button>
												</form>
											</div>
										</div>
									</div>
								</div>

								<!-- Modal ACTIVATE CARD -->
								<div class="modal fade" id="<?php echo $card->id; ?>-modal-activate" tabindex="-1" aria-labelledby="<?php echo $card->id; ?>-modal-activateLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="<?php echo $card->id; ?>-modal-activateLabel"><?php _e('Activate card', 'doenanova'); ?></h5>
												<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
												Tem certeza que deseja usar o cartão <i class="fab fa-cc-<?php echo strtolower($card->brand); ?>"></i> final <?php echo $card->last4; ?> para futuras doações?
											</div>
											<div class="modal-footer">
												<form id="activate_card" action="<?php echo get_admin_url(); ?>admin-post.php" method="POST">
													<input type='hidden' name='action' value='stripe_activate_card' />
													<input type="hidden" name='card_id' value='<?php echo $card->id; ?>' />
													<input type="hidden" name="current_url" id="current_url" value="<?php echo get_permalink(); ?>">
													<button type="button" class="btn btn-danger load-on-click"><?php _e('Yes, use this card please', 'doenanova'); ?></button>
												</form>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>




				<?php } ?>

			<?php } else { ?>

				<h3 class="empty-notification"><?php _e('No saved card.', 'doenanova'); ?></h3>
				<h5 class="empty-notification"><?php _e("Once you make a donation you'll see your card information here.", 'doenanova'); ?></h5>

			<?php } ?>


		</div><!-- #cartoes-salvos -->


		<?php
		if (count($cardList) < 10) {
		?>
			<!-- Button trigger modal -->
			<div class="d-grid gap-2 col mx-auto mt-3">
				<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCardModal">
					<?php _e('Add card', 'doenanova'); ?>
				</button>
			</div>

			<!-- Modal -->
			<div class="modal fade" id="addCardModal" tabindex="-1" aria-labelledby="addCardModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="addCardModalLabel"><?php _e('Add card', 'doenanova'); ?></h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<form id="form-add-card" class="mt-3 mb-5" action="<?php echo get_admin_url(); ?>admin-post.php" method="POST">

								<?php //THIS FIELD WILL DETERMINE WHAT ACTION THE FROM SHOULD DO - relating to doe-na-nova-form-doar.php and stripe-checkout.php 
								?>
								<input type='hidden' name='action' value='stripe_add_card' />
								<?php if (is_user_logged_in()) { ?>
									<input type="hidden" name="user_email" id="user_email" value="<?php echo $current_user_email; ?>">
								<?php } ?>
								<input type="hidden" name="user_name" id="user_name" value="<?php echo $current_user_name; ?>">
								<input type="hidden" name="stripe-pk" id="stripe-pk" value="<?= $stripe_publishable_key ?>">
								<input type="hidden" name="current_url" id="current_url" value="<?php echo get_permalink(); ?>">

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

								<div class="d-grid gap-2 col mx-auto">
									<button id="add-card-submit" class="btn btn-primary btn-block"><?php _e('Add card', 'doenanova'); ?></button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		<?php
		}
		?>



		<?php
		// if (!empty($cardList->data)) {
		// 	if ($cardList->has_more) { 
		?>

		<?php
		/**
		 * AJAX loadmore_cards
		 */
		?>
		<!-- <div id="loadmore_cards-btn_container"> -->

		<!-- Fields to send to AJAX -->
		<!-- <input type="hidden" id="last_card" value="<?php echo $card->id ?>" /> -->

		<!-- <script src="https://js.stripe.com/v3/"></script> -->

		<!-- Fail message -->
		<!-- <div id="loadmore_cards-fail-message" class="alert alert-danger loadmore_cards-message"><?php _e('Cards could not be loaded.', 'doenanova'); ?></div> -->

		<!-- Load more Button -->
		<!-- <div class="d-grid gap-2">
						<a id="loadmore_cards-btn" href="#" class="btn btn-primary"><?php _e('Show more', 'doenanova'); ?></a>
					</div> -->

		<!-- </div> -->


		<?php
		// }
		// } //if ($charges->has_more) 
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