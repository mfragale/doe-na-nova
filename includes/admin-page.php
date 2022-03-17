<?php

/***************
 * Admin page options
 ***************/

function doenanova_options_page()
{

	global $doenanova_options;

	ob_start(); ?>
	<div class="wrap">
		<h2><?php _e('Easy Ofering Plugin Options', 'doenanova'); ?></h2>

		<form method="post" action="options.php">

			<?php settings_fields('doenanova_settings_group'); ?>

			<h3><?php _e('Stripe API keys', 'doenanova'); ?></h3>

			<h4><?php _e('Test keys', 'doenanova'); ?></h4>
			<p>
				<label class="description" for="doenanova_settings[stripe_test_publishable_key]"><?php _e('TEST Publishable key:', 'doenanova'); ?></label>
				<input size="40" id="doenanova_settings[stripe_test_publishable_key]" name="doenanova_settings[stripe_test_publishable_key]" type="text" value="<?php if (isset($doenanova_options['stripe_test_publishable_key'])) {
																																									echo $doenanova_options['stripe_test_publishable_key'];
																																								}; ?>" />
			</p>
			<p>
				<label class="description" for="doenanova_settings[stripe_test_secret_key]"><?php _e('TEST Secret key:', 'doenanova'); ?></label>
				<input size="40" id="doenanova_settings[stripe_test_secret_key]" name="doenanova_settings[stripe_test_secret_key]" type="text" value="<?php if (isset($doenanova_options['stripe_test_secret_key'])) {
																																							echo $doenanova_options['stripe_test_secret_key'];
																																						}; ?>" />
			</p>

			<h4><?php _e('Enable Stripe LIVE mode', 'doenanova'); ?></h4>
			<p>
				<input id="doenanova_settings[enable_stripe_live_mode]" type="checkbox" name="doenanova_settings[enable_stripe_live_mode]" <?php if (isset($doenanova_options['enable_stripe_live_mode'])) {
																																				echo 'value="1" checked="checked"';
																																			} ?> />
				<label class="description" for="doenanova_settings[enable_stripe_live_mode]"><?php _e('Go LIVE', 'doenanova'); ?></label>
				<br /><small><?php _e('This will enable your site to receive REAL transactions.', 'doenanova'); ?></small>
			</p>

			<h4><?php _e('Show report', 'doenanova'); ?></h4>
			<p>
				<input id="doenanova_settings[show_report]" type="checkbox" name="doenanova_settings[show_report]" <?php if (isset($doenanova_options['show_report'])) {
																														echo 'value="1" checked="checked"';
																													} ?> />
				<label class="description" for="doenanova_settings[show_report]"><?php _e('Show report', 'doenanova'); ?></label>
				<br /><small><?php _e('This will enable you to see a report on the most important parameters of the plugin.', 'doenanova'); ?></small>
			</p>

			<h4><?php _e('Live keys', 'doenanova'); ?></h4>
			<p>
				<label class="description" for="doenanova_settings[stripe_live_publishable_key]"><?php _e('LIVE Publishable key:', 'doenanova'); ?></label>
				<input size="40" id="doenanova_settings[stripe_live_publishable_key]" name="doenanova_settings[stripe_live_publishable_key]" type="text" value="<?php if (isset($doenanova_options['stripe_live_publishable_key'])) {
																																									echo $doenanova_options['stripe_live_publishable_key'];
																																								}; ?>" />
			</p>
			<p>
				<label class="description" for="doenanova_settings[stripe_live_secret_key]"><?php _e('LIVE Secret key:', 'doenanova'); ?></label>
				<input size="40" id="doenanova_settings[stripe_live_secret_key]" name="doenanova_settings[stripe_live_secret_key]" type="text" value="<?php if (isset($doenanova_options['stripe_live_secret_key'])) {
																																							echo $doenanova_options['stripe_live_secret_key'];
																																						}; ?>" />
			</p>

			<hr />

			<h4><?php _e('Currency', 'doenanova'); ?></h4>
			<p>
				<?php $currencies = array('brl', 'usd', 'ars', 'aud', 'bob', 'cad', 'chf', 'clp', 'cop', 'crc', 'dkk', 'dop', 'eur', 'gbp', 'ils', 'mxn', 'nok', 'nzd', 'pab', 'pen', 'pyg', 'sek', 'uyu'); ?>
				<select id="doenanova_settings[currency]" name="doenanova_settings[currency]">
					<?php foreach ($currencies as $currency) { ?>
						<?php if ($doenanova_options['currency'] == $currency) {
							$selected_currency = 'selected="selected"';
						} else {
							$selected_currency = '';
						} ?>
						<option <?php echo $selected_currency; ?> value="<?php echo $currency; ?>"><?php echo $currency; ?></option>
					<?php } ?>
				</select>
			</p>


			<hr />

			<h4><?php _e('Donation purposes', 'doenanova'); ?></h4>
			<p>
				<textarea rows="10" cols="40" id="doenanova_settings[donation_purposes]" name="doenanova_settings[donation_purposes]"><?php if (isset($doenanova_options['donation_purposes'])) {
																																			echo $doenanova_options['donation_purposes'];
																																		}; ?></textarea>
			</p>



			<hr />


			<h3><?php _e('Pages', 'doenanova'); ?></h3>


			<p>
				<label class="description" for="doenanova_settings[page_donation_form]"><?php _e('Donation form:', 'doenanova'); ?></label>

				<?php
				// WP_Query arguments
				$args = array(
					'post_type'              => array('page'),
					'post_status'            => array('publish'),
					'order'                  => 'ASC',
					'orderby'                => 'title',
					'posts_per_page'         => '-1',
				);

				// The Query
				$list_pages = new WP_Query($args); ?>

				<?php if ($list_pages->have_posts()) { ?>
					<select id="doenanova_settings[page_donation_form]" name="doenanova_settings[page_donation_form]">
						<?php while ($list_pages->have_posts()) {
							$list_pages->the_post(); ?>

							<option value="<?php global $post;
											$slug = $post->post_name;
											echo $slug; ?>" <?php if (isset($doenanova_options['page_donation_form']) && $doenanova_options['page_donation_form'] == $slug) {
																echo 'checked selected="selected"';
															} ?>><?php the_title(); ?></option>

						<?php } ?>
					</select>
				<?php } else {
					// no posts found
				} ?>

				<br />

				<small><?php _e('This page will be the main donation page, where your donation form will be. Copy this shortcode and paste it on this page.', 'doenanova'); ?></small>
				<small>
					<pre><?php _e('[doe-na-nova-form-doar]', 'doenanova'); ?></pre>
				</small>

			</p>


			<p>
				<label class="description" for="doenanova_settings[page_donation_result]"><?php _e('Donation result:', 'doenanova'); ?></label>

				<?php if ($list_pages->have_posts()) { ?>
					<select id="doenanova_settings[page_donation_result]" name="doenanova_settings[page_donation_result]">
						<?php while ($list_pages->have_posts()) {
							$list_pages->the_post(); ?>

							<option value="<?php global $post;
											$slug = $post->post_name;
											echo $slug; ?>" <?php if (isset($doenanova_options['page_donation_result']) && $doenanova_options['page_donation_result'] == $slug) {
																echo 'checked selected="selected"';
															} ?>><?php the_title(); ?></option>

						<?php } ?>
					</select>
				<?php } else {
					// no posts found
				} ?>

				<br />

				<small><?php _e('This page will be where the result (successful or unsuccessful) of the donation appears. Copy this shortcode and paste it on this page.', 'doenanova'); ?></small>
				<small>
					<pre><?php _e('[doe-na-nova-resultado-doacao]', 'doenanova'); ?></pre>
				</small>

			</p>


			<p>
				<label class="description" for="doenanova_settings[page_recent_transactions]"><?php _e('Recent transactions:', 'doenanova'); ?></label>

				<?php if ($list_pages->have_posts()) { ?>
					<select id="doenanova_settings[page_recent_transactions]" name="doenanova_settings[page_recent_transactions]">
						<?php while ($list_pages->have_posts()) {
							$list_pages->the_post(); ?>

							<option value="<?php global $post;
											$slug = $post->post_name;
											echo $slug; ?>" <?php if (isset($doenanova_options['page_recent_transactions']) && $doenanova_options['page_recent_transactions'] == $slug) {
																echo 'checked selected="selected"';
															} ?>><?php the_title(); ?></option>

						<?php } ?>
					</select>
				<?php } else {
					// no posts found
				} ?>

				<br />

				<small><?php _e('This page will be where the user can see his recent transactions. Copy this shortcode and paste it on this page.', 'doenanova'); ?></small>
				<small>
					<pre><?php _e('[doe-na-nova-transacoes]', 'doenanova'); ?></pre>
				</small>

			</p>


			<p>
				<label class="description" for="doenanova_settings[page_recurring_donations]"><?php _e('Recurring donations:', 'doenanova'); ?></label>

				<?php if ($list_pages->have_posts()) { ?>
					<select id="doenanova_settings[page_recurring_donations]" name="doenanova_settings[page_recurring_donations]">
						<?php while ($list_pages->have_posts()) {
							$list_pages->the_post(); ?>

							<option value="<?php global $post;
											$slug = $post->post_name;
											echo $slug; ?>" <?php if (isset($doenanova_options['page_recurring_donations']) && $doenanova_options['page_recurring_donations'] == $slug) {
																echo 'checked selected="selected"';
															} ?>><?php the_title(); ?></option>

						<?php } ?>
					</select>
				<?php } else {
					// no posts found
				} ?>

				<br />

				<small><?php _e('This page will be where the user can see his recurring (weekly, monthly, yearly) donations. Copy this shortcode and paste it on this page.', 'doenanova'); ?></small>
				<small>
					<pre><?php _e('[doe-na-nova-recorrentes]', 'doenanova'); ?></pre>
				</small>

			</p>


			<p>
				<label class="description" for="doenanova_settings[page_saved_card]"><?php _e('Saved card:', 'doenanova'); ?></label>

				<?php if ($list_pages->have_posts()) { ?>
					<select id="doenanova_settings[page_saved_card]" name="doenanova_settings[page_saved_card]">
						<?php while ($list_pages->have_posts()) {
							$list_pages->the_post(); ?>

							<option value="<?php global $post;
											$slug = $post->post_name;
											echo $slug; ?>" <?php if (isset($doenanova_options['page_saved_card']) && $doenanova_options['page_saved_card'] == $slug) {
																echo 'checked selected="selected"';
															} ?>><?php the_title(); ?></option>

						<?php } ?>
					</select>
				<?php } else {
					// no posts found
				} ?>

				<br />

				<small><?php _e('This page will be where the user can see his saved card. Copy this shortcode and paste it on this page.', 'doenanova'); ?></small>
				<small>
					<pre><?php _e('[doe-na-nova-cartoes]', 'doenanova'); ?></pre>
				</small>

			</p>







			<hr />


			<h3><?php _e('Contact information', 'doenanova'); ?></h3>


			<p>
				<label class="description" for="doenanova_settings[support_email]"><?php _e('Support Email:', 'doenanova'); ?></label>
				<input size="40" id="doenanova_settings[support_email]" name="doenanova_settings[support_email]" type="text" value="<?php if (isset($doenanova_options['support_email'])) {
																																		echo $doenanova_options['support_email'];
																																	}; ?>" />
			</p>







			<?php // Restore original Post Data for the $list_pages query
			wp_reset_postdata(); ?>









			<!-- Submut button -->
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Options', 'doenanova'); ?>" />
			</p>
			<!-- Submut button -->



		</form>

	</div>
<?php
	echo ob_get_clean();
}











function doenanova_add_options_link()
{
	add_options_page('Doe na Nova - Opções do Plugin', 'Doe na Nova', 'manage_options', 'doenanova-options', 'doenanova_options_page');
}


function doenanova_register_settings()
{
	// creates our settings in the options table
	register_setting('doenanova_settings_group', 'doenanova_settings');
}




?>