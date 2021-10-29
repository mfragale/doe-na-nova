	<div class="card mt-5 mb-5">
		<div class="card-body">
			<div class="row">
				<div class="col">
					<p><?php _e('Not registered yet?', 'doenanova'); ?></p>
					<a class="btn btn-primary" href="<?php echo esc_url(wp_registration_url()); ?>"><?php _e('Register', 'doenanova'); ?></a>
				</div>
				<div class="col">
					<p><?php _e('Login to make a donation.', 'doenanova'); ?></p>
					<a class="btn btn-outline-secondary" href="<?php echo esc_url(wp_login_url(home_url())); ?>"><?php _e('Login', 'doenanova'); ?></a>
				</div>
			</div>
		</div>
	</div>